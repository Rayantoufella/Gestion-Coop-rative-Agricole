import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { creerAcheteur, getAcheteur, modifierAcheteur } from '../../api/acheteurs'
import Icone from '../../components/Icone'
import ErreurChamp from '../../components/ErreurChamp'
import MessageErreur from '../../components/MessageErreur'

// Formulaire pour ajouter un acheteur, ou le modifier s'il y a un id dans l'adresse.
function FormulaireAcheteur() {
  const { id } = useParams()
  const navigate = useNavigate()

  // Un seul state pour tous les champs : chaque clé = nom de la colonne dans la base.
  const [form, setForm] = useState({
    nom: '',
    telephone: '',
  })
  const [erreurs, setErreurs] = useState({})
  const [messageErreur, setMessageErreur] = useState('')
  const [chargement, setChargement] = useState(false)
  const [envoi, setEnvoi] = useState(false)

  // En modification, on charge l'acheteur pour remplir le formulaire.
  useEffect(() => {
    // Demande l'acheteur à l'API et copie ses valeurs dans le formulaire.
    async function chargerAcheteur() {
      setChargement(true)
      try {
        const acheteur = await getAcheteur(id)
        setForm({
          nom: acheteur.nom,
          telephone: acheteur.telephone,
        })
      } catch (e) {
        console.log("Erreur pendant le chargement de l'acheteur", e)
        setMessageErreur('Impossible de charger cet acheteur.')
      }
      setChargement(false)
    }

    if (id) {
      chargerAcheteur()
    }
  }, [id])

  // Met à jour le champ modifié (grâce à son attribut name).
  function changerChamp(event) {
    setForm({ ...form, [event.target.name]: event.target.value })
  }

  // Envoie le formulaire à l'API, puis revient à la liste si tout va bien.
  async function envoyerFormulaire(event) {
    event.preventDefault()
    setErreurs({})
    setMessageErreur('')
    setEnvoi(true)

    try {
      if (id) {
        await modifierAcheteur(id, form)
      } else {
        await creerAcheteur(form)
      }
      navigate('/acheteurs')
    } catch (e) {
      if (e.response && e.response.status === 422) {
        setErreurs(e.response.data.errors)
      } else if (e.response && e.response.status === 403) {
        setMessageErreur("Vous n'avez pas le droit de faire cette action.")
      } else {
        setMessageErreur("L'enregistrement a échoué.")
      }
    }

    setEnvoi(false)
  }

  if (chargement) {
    return <p className="chargement">Chargement...</p>
  }

  return (
    <div className="fenetre">
      <form onSubmit={envoyerFormulaire} noValidate>
        <div className="fenetre-entete">
          <h2>{id ? "Modifier l'acheteur" : 'Nouvel acheteur'}</h2>
          <Link to="/acheteurs" className="bouton-icone bouton-icone-gris" aria-label="Fermer" title="Fermer">
            <Icone nom="fermer" />
          </Link>
        </div>

        <div className="fenetre-corps">
          <div className="fenetre-champs">
            {messageErreur && <MessageErreur texte={messageErreur} />}

            <div className="champ">
              <label htmlFor="nom">Nom de l'acheteur</label>
              <input id="nom" name="nom" type="text" value={form.nom} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.nom} />
            </div>

            <div className="champ">
              <label htmlFor="telephone">Téléphone</label>
              <input id="telephone" name="telephone" type="tel" value={form.telephone} onChange={changerChamp} />
              <span className="aide-champ">Format 05 28 22 41 10</span>
              <ErreurChamp messages={erreurs.telephone} />
            </div>
          </div>
        </div>

        <div className="fenetre-pied">
          <Link to="/acheteurs" className="bouton-secondaire">
            Annuler
          </Link>
          <button type="submit" className="bouton" disabled={envoi}>
            {envoi ? 'Enregistrement...' : "Enregistrer l'acheteur"}
          </button>
        </div>
      </form>
    </div>
  )
}

export default FormulaireAcheteur
