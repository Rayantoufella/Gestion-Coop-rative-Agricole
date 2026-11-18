import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { AuthProvider } from './context/AuthContext'
import RouteProtegee from './components/RouteProtegee'
import Layout from './components/Layout'
import Connexion from './pages/Connexion'
import Accueil from './pages/Accueil'
import ListeMembres from './pages/membres/ListeMembres'
import FormulaireMembre from './pages/membres/FormulaireMembre'
import ListeCotisations from './pages/cotisations/ListeCotisations'
import FormulaireCotisation from './pages/cotisations/FormulaireCotisation'
import ListeProduits from './pages/produits/ListeProduits'
import FormulaireProduit from './pages/produits/FormulaireProduit'
import ListeDepots from './pages/depots/ListeDepots'
import FormulaireDepot from './pages/depots/FormulaireDepot'
import Stock from './pages/stock/Stock'
import ListeAcheteurs from './pages/acheteurs/ListeAcheteurs'
import FormulaireAcheteur from './pages/acheteurs/FormulaireAcheteur'
import ListeVentes from './pages/ventes/ListeVentes'
import FormulaireVente from './pages/ventes/FormulaireVente'

// Composant principal : déclare toutes les routes (adresses) de l'application.
function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/connexion" element={<Connexion />} />

          <Route
            path="/"
            element={
              <RouteProtegee>
                <Layout>
                  <Accueil />
                </Layout>
              </RouteProtegee>
            }
          />

          {/* Membres : la liste pour les deux rôles, l'ajout et la modification pour l'admin */}
          <Route
            path="/membres"
            element={
              <RouteProtegee>
                <Layout>
                  <ListeMembres />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/membres/nouveau"
            element={
              <RouteProtegee role="admin">
                <Layout>
                  <FormulaireMembre />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/membres/:id/modifier"
            element={
              <RouteProtegee role="admin">
                <Layout>
                  <FormulaireMembre />
                </Layout>
              </RouteProtegee>
            }
          />

          {/* Cotisations : accès réservé à l'admin */}
          <Route
            path="/cotisations"
            element={
              <RouteProtegee role="admin">
                <Layout>
                  <ListeCotisations />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/cotisations/nouvelle"
            element={
              <RouteProtegee role="admin">
                <Layout>
                  <FormulaireCotisation />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/cotisations/:id/modifier"
            element={
              <RouteProtegee role="admin">
                <Layout>
                  <FormulaireCotisation />
                </Layout>
              </RouteProtegee>
            }
          />

          {/* Produits : liste, ajout et modification pour les deux rôles (comme le backend) */}
          <Route
            path="/produits"
            element={
              <RouteProtegee>
                <Layout>
                  <ListeProduits />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/produits/nouveau"
            element={
              <RouteProtegee>
                <Layout>
                  <FormulaireProduit />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/produits/:id/modifier"
            element={
              <RouteProtegee>
                <Layout>
                  <FormulaireProduit />
                </Layout>
              </RouteProtegee>
            }
          />

          {/* Pages communes aux deux rôles */}
          {/* Dépôts : liste, ajout et modification pour les deux rôles (comme le backend) */}
          <Route
            path="/depots"
            element={
              <RouteProtegee>
                <Layout>
                  <ListeDepots />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/depots/nouveau"
            element={
              <RouteProtegee>
                <Layout>
                  <FormulaireDepot />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/depots/:id/modifier"
            element={
              <RouteProtegee>
                <Layout>
                  <FormulaireDepot />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/stock"
            element={
              <RouteProtegee>
                <Layout>
                  <Stock />
                </Layout>
              </RouteProtegee>
            }
          />
          {/* Acheteurs : liste, ajout et modification pour les deux rôles */}
          <Route
            path="/acheteurs"
            element={
              <RouteProtegee>
                <Layout>
                  <ListeAcheteurs />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/acheteurs/nouveau"
            element={
              <RouteProtegee>
                <Layout>
                  <FormulaireAcheteur />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/acheteurs/:id/modifier"
            element={
              <RouteProtegee>
                <Layout>
                  <FormulaireAcheteur />
                </Layout>
              </RouteProtegee>
            }
          />
          {/* Ventes : liste et ajout pour les deux rôles (pas de modification) */}
          <Route
            path="/ventes"
            element={
              <RouteProtegee>
                <Layout>
                  <ListeVentes />
                </Layout>
              </RouteProtegee>
            }
          />
          <Route
            path="/ventes/nouvelle"
            element={
              <RouteProtegee>
                <Layout>
                  <FormulaireVente />
                </Layout>
              </RouteProtegee>
            }
          />

          {/* Adresse inconnue : retour à l'accueil */}
          <Route path="*" element={<Navigate to="/" />} />
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  )
}

export default App
