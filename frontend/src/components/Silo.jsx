// En dessous de ce pourcentage du déposé, le stock est considéré comme faible (comme le prototype).
const SEUIL_STOCK_FAIBLE = 25

// Dans le dessin, le mur du silo commence à y = 34 et s'arrête à y = 194 (hauteur 160).
const HAUT_DU_MUR = 34
const HAUTEUR_DU_MUR = 160

// Transforme une quantité 1250.5 en "1 250,50".
function formaterQuantite(quantite) {
  return Number(quantite).toLocaleString('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })
}

// Calcule le pourcentage disponible par rapport au total déposé (entre 0 et 100).
function calculerPourcentage(produit) {
  const depots = Number(produit.total_depots)
  const disponible = Number(produit.stock_disponible)
  if (depots <= 0 || disponible <= 0) {
    return 0
  }
  if (disponible >= depots) {
    return 100
  }
  return Math.round((disponible / depots) * 100)
}

// Dessine un silo rempli selon le stock disponible d'un produit, avec son nom et sa quantité.
// "produit" vient de l'API /dashboard : { id, nom, unite, total_depots, stock_disponible }.
// "taille" choisit la classe CSS : "accueil", "stock" ou "vente".
function Silo({ produit, taille }) {
  const pourcentage = calculerPourcentage(produit)
  const faible = pourcentage < SEUIL_STOCK_FAIBLE

  // Hauteur du grain dans le dessin : une partie de la hauteur du mur.
  const hauteurGrain = (HAUTEUR_DU_MUR * pourcentage) / 100
  const hautDuGrain = HAUT_DU_MUR + HAUTEUR_DU_MUR - hauteurGrain

  // Les motifs SVG (rainures, points...) ont besoin d'un identifiant unique dans la page.
  const id = 'silo-' + taille + '-' + produit.id

  return (
    <div className={'silo silo-' + taille}>
      {/* viewBox commence à -6 pour que le socle dépasse un peu du mur de chaque côté */}
      <svg
        className="silo-dessin"
        viewBox="-6 0 112 200"
        preserveAspectRatio="none"
        role="img"
        aria-label={produit.nom + ' : ' + formaterQuantite(produit.stock_disponible) + ' ' + produit.unite}
      >
        <defs>
          {/* Rainures horizontales du mur : une ligne toutes les 11 unités */}
          <pattern id={id + '-rainures'} width="100" height="11" patternUnits="userSpaceOnUse">
            <line className="silo-rainure" x1="0" y1="10.5" x2="100" y2="10.5" />
          </pattern>
          {/* Barreaux de l'échelle : une ligne toutes les 8 unités */}
          <pattern id={id + '-barreaux'} width="8" height="8" patternUnits="userSpaceOnUse">
            <line className="silo-echelle" x1="0" y1="7.5" x2="8" y2="7.5" />
          </pattern>
          {/* Petits points sur le grain */}
          <pattern id={id + '-points'} width="8" height="7" patternUnits="userSpaceOnUse">
            <circle className="silo-point" cx="4" cy="3.5" r="1.2" />
          </pattern>
          {/* Dégradé du grain, du haut (clair) vers le bas (foncé) */}
          <linearGradient id={id + '-degrade'} x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" className={faible ? 'silo-argile-clair' : 'silo-grain-clair'} />
            <stop offset="1" className={faible ? 'silo-argile-fonce' : 'silo-grain-fonce'} />
          </linearGradient>
        </defs>

        {/* Toit : petite cheminée + dôme arrondi */}
        <rect className="silo-toit" x="42" y="0" width="16" height="6" />
        <path className="silo-toit" d="M0 34 A50 28 0 0 1 100 34 Z" />

        {/* Mur du silo, puis ses rainures par-dessus */}
        <rect className="silo-mur" x="0" y={HAUT_DU_MUR} width="100" height={HAUTEUR_DU_MUR} />
        <rect fill={'url(#' + id + '-rainures)'} x="0" y={HAUT_DU_MUR} width="100" height={HAUTEUR_DU_MUR} />

        {/* Grain : dégradé jaune (ou rouge si le stock est faible), puis les points par-dessus.
            Le groupe "silo-remplissage" monte depuis le bas quand le silo apparaît (voir index.css). */}
        <g className="silo-remplissage">
          <rect fill={'url(#' + id + '-degrade)'} x="0" y={hautDuGrain} width="100" height={hauteurGrain} />
          <rect fill={'url(#' + id + '-points)'} x="0" y={hautDuGrain} width="100" height={hauteurGrain} />
          {pourcentage > 0 && <line className="silo-reflet" x1="0" y1={hautDuGrain} x2="100" y2={hautDuGrain} />}
        </g>

        {/* Échelle sur le côté : deux montants et des barreaux */}
        <line className="silo-echelle" x1="78" y1="42" x2="78" y2="194" />
        <line className="silo-echelle" x1="86" y1="42" x2="86" y2="194" />
        <rect fill={'url(#' + id + '-barreaux)'} x="78" y="42" width="8" height="152" />

        {/* Socle foncé, un peu plus large que le mur */}
        <rect className="silo-socle" x="-6" y="194" width="112" height="6" />
      </svg>

      <div>
        <div className="silo-nom">{produit.nom}</div>
        <div className="silo-valeur">
          {formaterQuantite(produit.stock_disponible)} {produit.unite}
        </div>
        {faible ? (
          <div className="silo-faible">Stock faible</div>
        ) : (
          <div className="silo-note">{pourcentage} % du déposé</div>
        )}
      </div>
    </div>
  )
}

export default Silo
