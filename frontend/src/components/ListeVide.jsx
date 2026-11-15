import Icone from './Icone'

// Bloc affiché quand une liste est vide : une icône dans un rond, un message,
// et éventuellement un bouton (passé entre les balises, dans "children").
function ListeVide({ icone, message, children }) {
  return (
    <div className="liste-vide">
      <div className="liste-vide-rond">
        <Icone nom={icone} taille={26} />
      </div>
      <p className="liste-vide-message">{message}</p>
      {children}
    </div>
  )
}

export default ListeVide
