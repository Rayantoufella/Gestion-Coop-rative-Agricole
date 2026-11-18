import { useContext } from 'react'
import { NavLink, useNavigate } from 'react-router-dom'
import { AuthContext } from '../context/AuthContext'
import Logo from './Logo'
import Icone from './Icone'

// Menu de l'administrateur.
const menuAdmin = [
  { chemin: '/', libelle: 'Tableau de bord', icone: 'tableau' },
  { chemin: '/membres', libelle: 'Membres', icone: 'membres' },
  { chemin: '/cotisations', libelle: 'Cotisations', icone: 'cotisations' },
  { chemin: '/produits', libelle: 'Produits', icone: 'produits' },
  { chemin: '/depots', libelle: 'Dépôts de stock', icone: 'depots' },
  { chemin: '/stock', libelle: 'Stock', icone: 'stock' },
  { chemin: '/acheteurs', libelle: 'Acheteurs', icone: 'acheteurs' },
  { chemin: '/ventes', libelle: 'Ventes groupées', icone: 'ventes' },
]

// Menu du gestionnaire.
const menuGestionnaire = [
  { chemin: '/', libelle: 'Tableau de bord', icone: 'tableau' },
  { chemin: '/membres', libelle: 'Membres', icone: 'membres' },
  { chemin: '/produits', libelle: 'Produits', icone: 'produits' },
  { chemin: '/depots', libelle: 'Dépôts de stock', icone: 'depots' },
  { chemin: '/stock', libelle: 'Stock', icone: 'stock' },
  { chemin: '/acheteurs', libelle: 'Acheteurs', icone: 'acheteurs' },
  { chemin: '/ventes', libelle: 'Ventes groupées', icone: 'ventes' },
]

// Mise en page commune : barre latérale à gauche, contenu de la page à droite.
function Layout({ children }) {
  const { utilisateur, deconnexion } = useContext(AuthContext)
  const navigate = useNavigate()

  // On choisit le menu selon le rôle de l'utilisateur.
  const menu = utilisateur.role === 'admin' ? menuAdmin : menuGestionnaire
  const nomRole = utilisateur.role === 'admin' ? 'Administrateur' : 'Gestionnaire'

  // Déconnecte l'utilisateur puis l'envoie sur la page de connexion.
  async function cliquerDeconnexion() {
    await deconnexion()
    navigate('/connexion')
  }

  return (
    <div className="layout">
      <aside className="barre-laterale">
        <div className="barre-laterale-entete">
          <Logo />
          <div className="barre-laterale-texte">
            <div className="logo-nom">GCA</div>
            <div className="barre-laterale-sous-titre">Coopérative agricole</div>
          </div>
        </div>

        <nav className="menu" aria-label="Navigation principale">
          {menu.map((element) => (
            // NavLink = Link qui ajoute la classe "active" quand on est sur cette page.
            // "end" : le lien "/" n'est actif que sur "/" exactement (sinon il le serait partout).
            <NavLink
              key={element.chemin}
              to={element.chemin}
              end
              className="menu-lien"
              title={element.libelle}
            >
              <Icone nom={element.icone} />
              <span>{element.libelle}</span>
            </NavLink>
          ))}
        </nav>

        <div className="barre-laterale-pied">
          <div className="utilisateur">
            <div className="utilisateur-nom">{utilisateur.name}</div>
            <div className="utilisateur-role">{nomRole}</div>
          </div>
          <button
            type="button"
            className="bouton-icone bouton-deconnexion"
            onClick={cliquerDeconnexion}
            aria-label="Se déconnecter"
            title="Se déconnecter"
          >
            <Icone nom="deconnexion" />
          </button>
        </div>
      </aside>

      <main className="contenu">{children}</main>
    </div>
  )
}

export default Layout
