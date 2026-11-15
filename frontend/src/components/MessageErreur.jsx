import Icone from './Icone'

// Encadré rouge avec une icône, pour afficher une erreur générale.
function MessageErreur({ texte }) {
  return (
    <p className="message-erreur" role="alert">
      <Icone nom="alerte" />
      <span>{texte}</span>
    </p>
  )
}

export default MessageErreur
