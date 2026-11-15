import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { creerProduit, getProduit, modifierProduit } from '../../api/produits'
import Icone from '../../components/Icone'
import ErreurChamp from '../../components/ErreurChamp'
import MessageErreur from '../../components/MessageErreur'

// Les choix possibles, repris du prototype.
const categories = ['Céréales', 'Légumes', 'Fruits', 'Légumineuses']
const unites = ['kg', 'sac', 'tonne', 'litre']

// Formulaire pour ajouter un produit, ou le modifier s'il y a un id dans l'adresse.
function FormulaireProduit() {
  const { id } = useParams()
  const navigate = useNavigate()

  // Un seul state pour tous les champs : chaque clé = nom de la colonne dans la base.
  const [form, setForm] = useState({
    nom: '',
    categorie: '',
    unite: '',
  })
  const [erreurs, setErreurs] = useState({})
  const [messageErreur, setMessageErreur] = useState('')
  const [chargement, setChargement] = useState(false)
  const [envoi, setEnvoi] = useState(false)

  // En modification, on charge le produit pour remplir le formulaire.
  useEffect(() => {
    // Demande le produit à l'API et copie ses valeurs dans le formulaire.
    async function chargerProduit() {
      setChargement(true)
      try {
        const produit = await getProduit(id)
        setForm({
          nom: produit.nom,
          categorie: produit.categorie,
          unite: produit.unite,
        })
      } catch (e) {
        console.log('Erreur pendant le chargement du produit', e)
        setMessageErreur('Impossible de charger ce produit.')
      }
      setChargement(false)
    }

    if (id) {
      chargerProduit()
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
        await modifierProduit(id, form)
      } else {
        await creerProduit(form)
      }
      navigate('/produits')
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
          <h2>{id ? 'Modifier le produit' : 'Nouveau produit'}</h2>
          <Link to="/produits" className="bouton-icone bouton-icone-gris" aria-label="Fermer" title="Fermer">
            <Icone nom="fermer" />
          </Link>
        </div>

        <div className="fenetre-corps">
          <div className="fenetre-champs">
            {messageErreur && <MessageErreur texte={messageErreur} />}

            <div className="champ">
              <label htmlFor="nom">Nom du produit</label>
              <input id="nom" name="nom" type="text" value={form.nom} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.nom} />
            </div>

            <div className="champ">
              <label htmlFor="categorie">Catégorie</label>
              <select id="categorie" name="categorie" value={form.categorie} onChange={changerChamp}>
                <option value="">Choisissez une catégorie</option>
                {categories.map((categorie) => (
                  <option key={categorie} value={categorie}>
                    {categorie}
                  </option>
                ))}
              </select>
              <ErreurChamp messages={erreurs.categorie} />
            </div>

            <div className="champ">
              <label htmlFor="unite">Unité</label>
              <select id="unite" name="unite" value={form.unite} onChange={changerChamp}>
                <option value="">Choisissez une unité</option>
                {unites.map((unite) => (
                  <option key={unite} value={unite}>
                    {unite}
                  </option>
                ))}
              </select>
              <ErreurChamp messages={erreurs.unite} />
            </div>
          </div>
        </div>

        <div className="fenetre-pied">
          <Link to="/produits" className="bouton-secondaire">
            Annuler
          </Link>
          <button type="submit" className="bouton" disabled={envoi}>
            {envoi ? 'Enregistrement...' : 'Enregistrer le produit'}
          </button>
        </div>
      </form>
    </div>
  )
}

export default FormulaireProduit
