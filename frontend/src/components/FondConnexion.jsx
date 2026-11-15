import { useEffect, useState } from 'react'

// ----- Dimensions de la scène (en unités du dessin SVG, pas en pixels) -----
const LARGEUR_SILO = 100
const ECART = 45
const PREMIER_X = 40
const SOL = 380 // ligne du sol
const TAPIS = 30 // hauteur du tapis roulant
const NIVEAU_PLEIN = 0.92 // un silo est "plein" à 92 %

// Les 5 silos : position x et hauteur du corps (un sur deux est un peu plus haut).
const silos = [
  { x: PREMIER_X, hauteur: 230 },
  { x: PREMIER_X + (LARGEUR_SILO + ECART), hauteur: 255 },
  { x: PREMIER_X + (LARGEUR_SILO + ECART) * 2, hauteur: 230 },
  { x: PREMIER_X + (LARGEUR_SILO + ECART) * 3, hauteur: 255 },
  { x: PREMIER_X + (LARGEUR_SILO + ECART) * 4, hauteur: 230 },
]

// État de départ de l'animation : niveau de chaque silo (entre 0 et 1) et silo qui se remplit.
const sceneDeDepart = {
  niveaux: [0.55, 0.25, 0.4, 0.6, 0.3],
  actuel: 0,
  compteur: 0,
}

// Calcule l'étape suivante de l'animation (appelée toutes les 50 ms).
// Le silo "actuel" se remplit, les autres se vident lentement.
function calculerEtapeSuivante(scene) {
  const niveaux = scene.niveaux.map((niveau, index) => {
    if (index === scene.actuel) {
      return Math.min(NIVEAU_PLEIN, niveau + 0.0033)
    }
    return Math.max(0.12, niveau - 0.0006)
  })

  let actuel = scene.actuel
  // Quand le silo actuel est plein, on choisit au hasard un autre silo.
  if (niveaux[actuel] >= NIVEAU_PLEIN) {
    const decalage = 1 + Math.floor(Math.random() * (silos.length - 1))
    actuel = (actuel + decalage) % silos.length
  }

  return { niveaux: niveaux, actuel: actuel, compteur: scene.compteur + 1 }
}

// Dessine le contour d'un silo : corps rectangulaire + dôme arrondi en haut.
function contourSilo(silo) {
  const rayon = LARGEUR_SILO / 2
  const haut = SOL - silo.hauteur
  return (
    'M' + silo.x + ' ' + SOL +
    ' L' + silo.x + ' ' + haut +
    ' A' + rayon + ' ' + rayon + ' 0 0 1 ' + (silo.x + LARGEUR_SILO) + ' ' + haut +
    ' L' + (silo.x + LARGEUR_SILO) + ' ' + SOL + ' Z'
  )
}

// Décor animé de la page de connexion : un tapis roulant verse du grain dans des silos.
function FondConnexion() {
  const [scene, setScene] = useState(sceneDeDepart)

  // Au démarrage, on lance un minuteur qui fait avancer l'animation.
  useEffect(() => {
    // Si l'utilisateur a demandé moins d'animations dans son système, on ne bouge pas.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return
    }

    const minuteur = setInterval(() => {
      setScene((ancienneScene) => calculerEtapeSuivante(ancienneScene))
    }, 50)

    // Quand on quitte la page, on arrête le minuteur.
    return () => clearInterval(minuteur)
  }, [])

  const debutTapis = PREMIER_X - 30
  const finTapis = silos[4].x + LARGEUR_SILO + 30

  return (
    <div className="fond-connexion" aria-hidden="true">
      {/* Le sol : une bande en bas de l'écran */}
      <div className="sol"></div>

      <svg className="scene-silos" viewBox="0 0 760 380" preserveAspectRatio="xMidYMax meet" overflow="visible">
        <defs>
          <linearGradient id="grain-scene" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" className="silo-grain-clair" />
            <stop offset="1" className="silo-grain-fonce" />
          </linearGradient>
          {/* Une "découpe" par silo pour que le grain reste à l'intérieur du contour */}
          {silos.map((silo, index) => (
            <clipPath key={index} id={'decoupe-silo-' + index}>
              <path d={contourSilo(silo)} />
            </clipPath>
          ))}
        </defs>

        {/* Tapis roulant : deux rails, des tirets qui avancent, deux poteaux */}
        <line className="scene-trait" x1={debutTapis} y1={TAPIS} x2={finTapis} y2={TAPIS} />
        <line className="scene-trait" x1={debutTapis} y1={TAPIS + 6} x2={finTapis} y2={TAPIS + 6} />
        <line
          className="scene-trait scene-tapis"
          x1={debutTapis}
          y1={TAPIS + 3}
          x2={finTapis}
          y2={TAPIS + 3}
          strokeDashoffset={-scene.compteur * 3}
        />
        <line className="scene-trait" x1={debutTapis + 10} y1={TAPIS + 6} x2={debutTapis + 10} y2={SOL} />
        <line className="scene-trait" x1={finTapis - 10} y1={TAPIS + 6} x2={finTapis - 10} y2={SOL} />

        {silos.map((silo, index) => {
          const milieu = silo.x + LARGEUR_SILO / 2
          const haut = SOL - silo.hauteur
          const surface = SOL - silo.hauteur * scene.niveaux[index]
          const enRemplissage = index === scene.actuel

          return (
            <g key={index}>
              {/* Tuyau entre le tapis et le haut du silo */}
              <line className="scene-trait" x1={milieu} y1={haut - LARGEUR_SILO / 2} x2={milieu} y2={TAPIS + 6} />

              {/* Intérieur clair du silo */}
              <path className="scene-coque" d={contourSilo(silo)} />

              {/* Grain, découpé à la forme du silo */}
              <rect
                clipPath={'url(#decoupe-silo-' + index + ')'}
                fill="url(#grain-scene)"
                x={silo.x}
                y={surface}
                width={LARGEUR_SILO}
                height={SOL - surface}
              />

              {/* Contour et 4 anneaux horizontaux */}
              <path className="scene-trait" d={contourSilo(silo)} fill="none" />
              <line className="scene-anneau" x1={silo.x} y1={SOL - silo.hauteur * 0.2} x2={silo.x + LARGEUR_SILO} y2={SOL - silo.hauteur * 0.2} />
              <line className="scene-anneau" x1={silo.x} y1={SOL - silo.hauteur * 0.4} x2={silo.x + LARGEUR_SILO} y2={SOL - silo.hauteur * 0.4} />
              <line className="scene-anneau" x1={silo.x} y1={SOL - silo.hauteur * 0.6} x2={silo.x + LARGEUR_SILO} y2={SOL - silo.hauteur * 0.6} />
              <line className="scene-anneau" x1={silo.x} y1={SOL - silo.hauteur * 0.8} x2={silo.x + LARGEUR_SILO} y2={SOL - silo.hauteur * 0.8} />

              {/* Filet de grains qui tombe du tapis dans le silo qui se remplit */}
              {enRemplissage && (
                <line
                  className="scene-filet"
                  x1={milieu}
                  y1={TAPIS + 6}
                  x2={milieu}
                  y2={surface}
                  strokeDashoffset={-scene.compteur * 4}
                />
              )}
              {enRemplissage && (
                <circle className="scene-point" cx={milieu} cy={TAPIS - 8} r={scene.compteur % 10 < 5 ? 3 : 4} />
              )}

              {/* Pourcentage sous le silo */}
              <text className="scene-texte" x={milieu} y={SOL + 20} textAnchor="middle">
                {Math.round(scene.niveaux[index] * 100)}%
              </text>
            </g>
          )
        })}
      </svg>
    </div>
  )
}

export default FondConnexion
