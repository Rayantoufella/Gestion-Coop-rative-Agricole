import Icone from './Icone'

// Affiche l'erreur de validation sous un champ (le premier message renvoyé par Laravel).
// "messages" est un tableau, par exemple ["Le champ nom est obligatoire."], ou rien.
function ErreurChamp({ messages }) {
  if (!messages) {
    return null
  }

  return (
    <p className="erreur-champ" role="alert">
      <Icone nom="alerte" taille={16} />
      <span>{messages[0]}</span>
    </p>
  )
}

export default ErreurChamp
