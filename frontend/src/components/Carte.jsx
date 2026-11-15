// Un composant reçoit des "props" (des données) depuis son parent.
// Ici on "déstructure" { titre, valeur } directement dans les paramètres.
// Comme dans le prototype ("Registre du mois"), on affiche le gros chiffre puis le texte.
function Carte({ titre, valeur }) {
  return (
    // Dans le JSX, les accolades { } insèrent une valeur JavaScript.
    // className="carte" applique le style défini dans index.css.
    <div className="carte">
      <p className="carte-valeur">{valeur}</p>
      <p className="carte-titre">{titre}</p>
    </div>
  )
}

// export default rend le composant importable depuis un autre fichier.
export default Carte
