// Affiche le logo du prototype : un silo (toit arrondi, bande au milieu) avec du grain jaune en bas.
// La couleur des traits vient de "currentColor" : elle suit la couleur du texte autour.
// Avec la prop "anime", le logo se dessine trait par trait (utilisé sur la page de connexion).
function Logo({ anime }) {
  return (
    <svg className={anime ? 'logo logo-anime' : 'logo'} viewBox="0 0 48 56" fill="none" aria-hidden="true">
      <path className="logo-grain" d="M12 39 H36 V48 H12Z" />
      {/* pathLength="100" : chaque trait mesure "100", ce qui simplifie l'animation en CSS */}
      <g stroke="currentColor" strokeWidth="2.6" strokeLinecap="round" strokeLinejoin="round">
        <path className="logo-trait" pathLength="100" d="M10 22 A14 12 0 0 1 38 22" />
        <path className="logo-trait" pathLength="100" d="M10 22 V50" />
        <path className="logo-trait" pathLength="100" d="M38 22 V50" />
        <path className="logo-trait" pathLength="100" d="M10 32 H38" />
        <path className="logo-trait" pathLength="100" d="M4 50 H44" />
        <path className="logo-trait" pathLength="100" d="M21 6 H27 V10" />
      </g>
    </svg>
  )
}

export default Logo
