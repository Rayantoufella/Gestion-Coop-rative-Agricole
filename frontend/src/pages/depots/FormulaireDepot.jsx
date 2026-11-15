import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { creerDepot, getDepot, modifierDepot } from '../../api/depots'
import { getTousLesMembres } from '../../api/membres'
import { getTousLesProduits } from '../../api/produits'
import Icone from '../../components/Icone'
import ErreurChamp from '../../components/ErreurChamp'
import MessageErreur from '../../components/MessageErreur'

// Formulaire pour enregistrer un dépôt, ou le modifier s'il y a un id dans l'adresse.
function FormulaireDepot() {
  const { id } = useParams()
  const navigate = useNavigate()

  // Un seul state pour tous les champs : chaque clé = nom de la colonne dans la base.
  const [form, setForm] = useState({
    membre_id: '',
    produit_id: '',
    quantite: '',
    date_depot: '',
  })
  const [membres, setMembres] = useState([])
  const [produits, setProduits] = useState([])
  const [erreurs, setErreurs] = useState({})
  const [messageErreur, setMessageErreur] = useState('')
  const [chargement, setChargement] = useState(true)
  const [envoi, setEnvoi] = useState(false)

  // Au démarrage : on charge les membres, les produits, et le dépôt si on modifie.
  useEffect(() => {
    // Demande à l'API les listes pour les select et, en modification, le dépôt.
    async function chargerDonnees() {
      setChargement(true)
      try {
        const listeMembres = await getTousLesMembres()
        setMembres(listeMembres)

        const listeProduits = await getTousLesProduits()
        setProduits(listeProduits)

        if (id) {
          const depot = await getDepot(id)
          setForm({
            membre_id: String(depot.membre_id),
            produit_id: String(depot.produit_id),
            quantite: depot.quantite,
            date_depot: depot.date_depot,
          })
        }
      } catch (e) {
        console.log('Erreur pendant le chargement', e)
        setMessageErreur('Impossible de charger les données du formulaire.')
      }
      setChargement(false)
    }

    chargerDonnees()
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
        await modifierDepot(id, form)
      } else {
        await creerDepot(form)
      }
      navigate('/depots')
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

  // On cherche le produit choisi pour afficher son unité à côté de "Quantité".
  const produitChoisi = produits.find((produit) => String(produit.id) === form.produit_id)

  if (chargement) {
    return <p className="chargement">Chargement...</p>
  }

  return (
    <div className="fenetre">
      <form onSubmit={envoyerFormulaire} noValidate>
        <div className="fenetre-entete">
          <h2>{id ? 'Modifier le dépôt' : 'Nouveau dépôt'}</h2>
          <Link to="/depots" className="bouton-icone bouton-icone-gris" aria-label="Fermer" title="Fermer">
            <Icone nom="fermer" />
          </Link>
        </div>

        <div className="fenetre-corps">
          <div className="fenetre-champs">
            {messageErreur && <MessageErreur texte={messageErreur} />}

            <div className="champ">
              <label htmlFor="membre_id">Membre</label>
              <select id="membre_id" name="membre_id" value={form.membre_id} onChange={changerChamp}>
                <option value="">Choisissez un membre</option>
                {membres.map((membre) => (
                  <option key={membre.id} value={String(membre.id)}>
                    {membre.code_membre} · {membre.prenom} {membre.nom}
                    {membre.statut === 'inactif' ? ' (inactif)' : ''}
                  </option>
                ))}
              </select>
              <ErreurChamp messages={erreurs.membre_id} />
            </div>

            <div className="champ">
              <label htmlFor="produit_id">Produit</label>
              <select id="produit_id" name="produit_id" value={form.produit_id} onChange={changerChamp}>
                <option value="">Choisissez un produit</option>
                {produits.map((produit) => (
                  <option key={produit.id} value={String(produit.id)}>
                    {produit.nom}
                  </option>
                ))}
              </select>
              <ErreurChamp messages={erreurs.produit_id} />
            </div>

            <div className="champ">
              <label htmlFor="quantite">
                Quantité{produitChoisi ? ' (' + produitChoisi.unite + ')' : ''}
              </label>
              <input
                id="quantite"
                name="quantite"
                type="number"
                step="0.01"
                min="0"
                value={form.quantite}
                onChange={changerChamp}
              />
              <ErreurChamp messages={erreurs.quantite} />
            </div>

            <div className="champ">
              <label htmlFor="date_depot">Date du dépôt</label>
              <input id="date_depot" name="date_depot" type="date" value={form.date_depot} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.date_depot} />
            </div>
          </div>
        </div>

        <div className="fenetre-pied">
          <Link to="/depots" className="bouton-secondaire">
            Annuler
          </Link>
          <button type="submit" className="bouton" disabled={envoi}>
            {envoi ? 'Enregistrement...' : 'Enregistrer le dépôt'}
          </button>
        </div>
      </form>
    </div>
  )
}

export default FormulaireDepot
