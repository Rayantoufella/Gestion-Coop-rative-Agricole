import Icone from './Icone'

// Barre en bas d'un tableau : "Lignes 1 à 10 sur 23" et les flèches Précédent / Suivant.
// "meta" vient de l'API Laravel : { current_page, last_page, from, to, total }.
// "onChangerPage" est appelée avec le numéro de la nouvelle page.
function Pagination({ meta, onChangerPage }) {
  const page = meta.current_page

  return (
    <div className="pagination">
      <span className="texte-doux">
        Lignes {meta.from} à {meta.to} sur {meta.total}
      </span>
      <div className="pagination-boutons">
        <button
          type="button"
          className="bouton-icone bouton-icone-cadre"
          onClick={() => onChangerPage(page - 1)}
          disabled={page === 1}
          aria-label="Page précédente"
        >
          <Icone nom="precedent" taille={18} />
        </button>
        <span className="pagination-texte">
          Page {page} / {meta.last_page}
        </span>
        <button
          type="button"
          className="bouton-icone bouton-icone-cadre"
          onClick={() => onChangerPage(page + 1)}
          disabled={page === meta.last_page}
          aria-label="Page suivante"
        >
          <Icone nom="suivant" taille={18} />
        </button>
      </div>
    </div>
  )
}

export default Pagination
