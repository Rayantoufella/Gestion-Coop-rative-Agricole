import { useEffect, useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { creerVente } from '../../api/ventes'
import { getTousLesAcheteurs } from '../../api/acheteurs'
import { getDashboard } from '../../api/dashboard'
import Icone from '../../components/Icone'
import Silo from '../../components/Silo'
import ErreurChamp from '../../components/ErreurChamp'
import MessageErreur from '../../components/MessageErreur'

// Transforme un nombre 1250.5 en "1 250,50".
function formaterNombre(nombre) {
  return Number(nombre).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// Formulaire pour enregistrer une nouvelle vente (une vente ne se modifie pas).
function FormulaireVente() {
  const navigate = useNavigate()

  // Un seul state pour tous les champs : chaque clé = nom de la colonne dans la base.
  const [form, setForm] = useState({
    produit_id: '',
    acheteur_id: '',
    date_vente: '',
    quantite: '',
    prix_unitaire: '',
  })
  // Liste des produits avec leur stock disponible (vient du tableau de bord).
  const [produits, setProduits] = useState([])
  const [acheteurs, setAcheteurs] = useState([])
  const [erreurs, setErreurs] = useState({})
  const [messageErreur, setMessageErreur] = useState('')
  const [chargement, setChargement] = useState(true)
  const [envoi, setEnvoi] = useState(false)

  // Au démarrage : on charge les produits (avec leur stock) et les acheteurs.
  useEffect(() => {
    // Demande à l'API les données nécessaires aux deux listes déroulantes.
    async function chargerDonnees() {
      setChargement(true)
      try {
        const tableauDeBord = await getDashboard()
        setProduits(tableauDeBord.stock)

        const listeAcheteurs = await getTousLesAcheteurs()
        setAcheteurs(listeAcheteurs)
      } catch (e) {
        console.log('Erreur pendant le chargement', e)
        setMessageErreur('Impossible de charger les données du formulaire.')
      }
      setChargement(false)
    }

    chargerDonnees()
  }, [])

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
      await creerVente(form)
      navigate('/ventes')
    } catch (e) {
      if (e.response && e.response.status === 422 && e.response.data.errors) {
        // Erreurs de validation : un message pour chaque champ.
        setErreurs(e.response.data.errors)
      } else if (e.response && e.response.status === 422) {
        // Stock insuffisant : l'API renvoie seulement un message et le stock disponible.
        setErreurs({
          quantite: ['Stock insuffisant. Stock disponible : ' + formaterNombre(e.response.data.stock_disponible)],
        })
      } else if (e.response && e.response.status === 403) {
        setMessageErreur("Vous n'avez pas le droit d'enregistrer une vente.")
      } else {
        setMessageErreur("L'enregistrement a échoué.")
      }
    }

    setEnvoi(false)
  }

  // On cherche le produit choisi pour afficher son stock disponible et son silo.
  const produitChoisi = produits.find((produit) => String(produit.id) === form.produit_id)

  // Montant total calculé pendant la saisie.
  const montantTotal = Number(form.quantite) * Number(form.prix_unitaire)

  if (chargement) {
    return <p className="chargement">Chargement...</p>
  }

  return (
    <div className="fenetre fenetre-large">
      <form onSubmit={envoyerFormulaire} noValidate>
        <div className="fenetre-entete">
          <h2>Nouvelle vente</h2>
          <Link to="/ventes" className="bouton-icone bouton-icone-gris" aria-label="Fermer" title="Fermer">
            <Icone nom="fermer" />
          </Link>
        </div>

        <div className="fenetre-corps">
          <div className="fenetre-champs">
            {messageErreur && <MessageErreur texte={messageErreur} />}

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
              <label htmlFor="acheteur_id">Acheteur</label>
              <select id="acheteur_id" name="acheteur_id" value={form.acheteur_id} onChange={changerChamp}>
                <option value="">Choisissez un acheteur</option>
                {acheteurs.map((acheteur) => (
                  <option key={acheteur.id} value={String(acheteur.id)}>
                    {acheteur.nom}
                  </option>
                ))}
              </select>
              <ErreurChamp messages={erreurs.acheteur_id} />
            </div>

            <div className="champ">
              <label htmlFor="date_vente">Date de la vente</label>
              <input id="date_vente" name="date_vente" type="date" value={form.date_vente} onChange={changerChamp} />
              <ErreurChamp messages={erreurs.date_vente} />
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
              <label htmlFor="prix_unitaire">Prix unitaire (DH)</label>
              <input
                id="prix_unitaire"
                name="prix_unitaire"
                type="number"
                step="0.01"
                min="0"
                value={form.prix_unitaire}
                onChange={changerChamp}
              />
              <ErreurChamp messages={erreurs.prix_unitaire} />
            </div>

            <div className="vente-resume">
              <span className="texte-doux">
                {produitChoisi
                  ? 'Stock disponible : ' + formaterNombre(produitChoisi.stock_disponible) + ' ' + produitChoisi.unite
                  : 'Choisissez un produit pour voir son stock disponible.'}
              </span>
              <div className="montant-total">
                <span className="texte-gras">Montant total</span>
                <span className="montant-total-valeur">{formaterNombre(montantTotal)} DH</span>
              </div>
            </div>
          </div>

          {/* À droite : le silo du produit choisi */}
          {/* key change avec le produit : le silo est recréé et se remplit à nouveau */}
          {produitChoisi && <Silo key={produitChoisi.id} produit={produitChoisi} taille="vente" />}
        </div>

        <div className="fenetre-pied">
          <Link to="/ventes" className="bouton-secondaire">
            Annuler
          </Link>
          <button type="submit" className="bouton" disabled={envoi}>
            {envoi ? 'Enregistrement...' : 'Enregistrer la vente'}
          </button>
        </div>
      </form>
    </div>
  )
}

export default FormulaireVente
