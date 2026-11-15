import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { creerMembre, getMembre, modifierMembre } from '../../api/membres'
import Icone from '../../components/Icone'
import ErreurChamp from '../../components/ErreurChamp'
import MessageErreur from '../../components/MessageErreur'

// Formulaire pour ajouter un membre, ou le modifier s'il y a un id dans l'adresse.
function FormulaireMembre() {
  const { id } = useParams()
  const navigate = useNavigate()

  // Un seul state pour tous les champs : chaque clé = nom de la colonne dans la base.
  const [form, setForm] = useState({
    code_membre: '',
    nom: '',
    prenom: '',
    telephone: '',
    date_adhesion: '',
    statut: 'actif',
  })
  const [erreurs, setErreurs] = useState({})
  const [messageErreur, setMessageErreur] = useState('')
  const [chargement, setChargement] = useState(false)
  const [envoi, setEnvoi] = useState(false)

  // En modification, on charge le membre pour remplir le formulaire.
  useEffect(() => {
    // Demande le membre à l'API et copie ses valeurs dans le formulaire.
    async function chargerMembre() {
      setChargement(true)
      try {
        const membre = await getMembre(id)
        setForm({
          code_membre: membre.code_membre,
          nom: membre.nom,
          prenom: membre.prenom,
          telephone: membre.telephone,
          date_adhesion: membre.date_adhesion,
          statut: membre.statut,
        })
      } catch (e) {
        console.log('Erreur pendant le chargement du membre', e)
        setMessageErreur('Impossible de charger ce membre.')
      }
      setChargement(false)
    }

    if (id) {
      chargerMembre()
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
        await modifierMembre(id, form)
      } else {
        await creerMembre(form)
      }
      navigate('/membres')
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
          <h2>{id ? 'Modifier le membre' : 'Nouveau membre'}</h2>
          <Link to="/membres" className="bouton-icone bouton-icone-gris" aria-label="Fermer" title="Fermer">
            <Icone nom="fermer" />
          </Link>
        </div>

        <div className="fenetre-corps">
          <div className="fenetre-champs">
            {messageErreur && <MessageErreur texte={messageErreur} />}

            <div className="champ">
              <label htmlFor="code_membre">Code membre</label>
              <input id="code_membre" name="code_membre" type="text" value={form.code_membre} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.code_membre} />
            </div>

            <div className="champ">
              <label htmlFor="nom">Nom</label>
              <input id="nom" name="nom" type="text" value={form.nom} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.nom} />
            </div>

            <div className="champ">
              <label htmlFor="prenom">Prénom</label>
              <input id="prenom" name="prenom" type="text" value={form.prenom} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.prenom} />
            </div>

            <div className="champ">
              <label htmlFor="telephone">Téléphone</label>
              <input id="telephone" name="telephone" type="tel" value={form.telephone} onChange={changerChamp} />
              <span className="aide-champ">Format 06 12 34 56 78</span>
              <ErreurChamp messages={erreurs.telephone} />
            </div>

            <div className="champ">
              <label htmlFor="date_adhesion">Date d'adhésion</label>
              <input id="date_adhesion" name="date_adhesion" type="date" value={form.date_adhesion} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.date_adhesion} />
            </div>

            <div className="champ">
              <label htmlFor="statut">Statut</label>
              <select id="statut" name="statut" value={form.statut} onChange={changerChamp}>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
              </select>
              <ErreurChamp messages={erreurs.statut} />
            </div>
          </div>
        </div>

        <div className="fenetre-pied">
          <Link to="/membres" className="bouton-secondaire">
            Annuler
          </Link>
          <button type="submit" className="bouton" disabled={envoi}>
            {envoi ? 'Enregistrement...' : 'Enregistrer le membre'}
          </button>
        </div>
      </form>
    </div>
  )
}

export default FormulaireMembre
