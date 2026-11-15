import { useEffect, useState } from 'react'
import { getDashboard } from '../../api/dashboard'
import Silo from '../../components/Silo'
import ListeVide from '../../components/ListeVide'
import MessageErreur from '../../components/MessageErreur'

// Transforme une quantité 1250.5 en "1 250,50".
function formaterQuantite(quantite) {
  return Number(quantite).toLocaleString('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })
}

// Page qui affiche le stock disponible de chaque produit (dépôts − ventes), sous forme de silos.
function Stock() {
  const [stock, setStock] = useState([])
  const [chargement, setChargement] = useState(true)
  const [erreur, setErreur] = useState('')

  // Au démarrage, on charge le stock depuis l'API.
  useEffect(() => {
    // Demande à l'API le tableau de bord et garde seulement la partie "stock".
    async function chargerStock() {
      setChargement(true)
      setErreur('')
      try {
        const reponse = await getDashboard()
        setStock(reponse.stock)
      } catch (e) {
        if (e.response && e.response.status === 403) {
          setErreur("Vous n'avez pas le droit de voir le stock.")
        } else {
          setErreur('Impossible de charger le stock.')
        }
      }
      setChargement(false)
    }

    chargerStock()
  }, [])

  return (
    <div>
      <div className="page-entete">
        <div>
          <h1>Stock</h1>
          <p className="page-intro">
            Stock disponible = dépôts − ventes. Il est calculé automatiquement et ne se saisit jamais.
          </p>
        </div>
      </div>

      {chargement && <p className="chargement">Chargement...</p>}

      {!chargement && erreur && <MessageErreur texte={erreur} />}

      {!chargement && !erreur && stock.length === 0 && (
        <div className="cadre-tableau">
          <ListeVide icone="stock" message="Aucun produit pour l'instant." />
        </div>
      )}

      {!chargement && !erreur && stock.length > 0 && (
        <div className="grille-stock">
          {stock.map((produit) => (
            <div key={produit.id} className="colonne-stock">
              <Silo produit={produit} taille="stock" />

              <div className="detail-stock">
                <div className="ligne-stock">
                  <span className="texte-doux">Déposé</span>
                  <span className="texte-gras">
                    {formaterQuantite(produit.total_depots)} {produit.unite}
                  </span>
                </div>
                <div className="ligne-stock">
                  <span className="texte-doux">Vendu</span>
                  <span className="texte-gras">
                    {formaterQuantite(produit.total_ventes)} {produit.unite}
                  </span>
                </div>
                <div className="ligne-stock ligne-stock-total">
                  <span>Disponible</span>
                  <span>
                    {formaterQuantite(produit.stock_disponible)} {produit.unite}
                  </span>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}

export default Stock
