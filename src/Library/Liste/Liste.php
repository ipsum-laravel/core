<?php
namespace Ipsum\Core\Library\Liste;

use Illuminate\Http\Request;

/**
* Affichage de liste
*
* Affichage de liste paginée avec posibilité de tri de recherches et de filtres
*
*/
class Liste
{
    /**
    * Nombres de ligne a afficher dans la liste
    * @var int
    */
    protected $nb_lignes = 25;

    /**
    * Nombres de ligne a afficher dans la liste
    * @var int
    */
    protected $ordre_defaut = 'desc';

    /**
    * Requête de base à effectuer
    * @var array
    */
    protected $requete;

    /**
    * Noms des inputs des filtres, sa valeur et leurs correspondances
    * @var array
    */
    protected $filtres = array();

    /**
    * Colonnes du tri et l'ordre
    * @var array
    */
    protected $tris = array();

    /**
    * Données complémentaires GET de l'url
    * @var array
    */
    protected $query_complementaires = null;


    protected $lignes;

    protected $parametres = array();

     /**
    * Dépendances
    */
    protected $request;

    /**
    * Constructor function.
    */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * Setter de requete
    * @param array $requete
    */
    public function setRequete($requete)
    {
        $this->requete = $requete;
    }

    /**
    * Setter de filtres
    * @param array $filtres
    */
    public function setFiltres($filtres)
    {
        $this->filtres = array();

        foreach ($filtres as $filtre) {
            if ($this->request->has($filtre['nom'])) {
                $filtre['valeur'] = $this->request->input($filtre['nom']);
            } elseif (!isset($filtre['valeur'])) {
                $filtre['valeur'] = null;
            }

            if (!empty($filtre['valeur'])) {
                $this->parametres[$filtre['nom']] = $filtre['valeur'];
            }

            $this->filtres[$filtre['nom']] = $filtre;
        }
    }

    /**
    * Setter de tri
    * @param array $tri
    */
    public function setTris($tris)
    {
        $this->tris = array();

        $query_tri = $this->request->input('tri', array());
        foreach ($tris as $tri) {
            if (isset($query_tri[$tri['nom']])) {
                $tri['ordre'] = $query_tri[$tri['nom']];
                $tri['actif'] = true;
            } elseif (!isset($tri['actif']) or count($query_tri) > 0) {
                $tri['ordre'] = isset($tri['ordre']) ? $tri['ordre'] : $this->ordre_defaut;
                $tri['actif'] = false;
            }

            if ($tri['actif']) {
                $this->parametres['tri'][$tri['nom']] = $tri['ordre'];
            }

            $this->tris[$tri['nom']] = $tri;
        }
    }

    /**
    * Setter de nb_lignes
    * @param int $nb_lignes
    */
    public function setNbLignes($nb_lignes)
    {
        if (!empty($nb_lignes) and  is_numeric($nb_lignes)) {
            $this->nb_lignes = $nb_lignes;
        }
    }

    /**
    * Setter de query_complementaires
    * @param array $query_complementaires
    */
    public function setQueryComplementaires($query_complementaires)
    {
        $this->query_complementaires = $query_complementaires;
        foreach($query_complementaires as $query => $value) {
            $this->parametres[$query] = $value;
        }
    }

    public function getPageCourante()
    {
        return $this->lignes->getCurrentPage();
    }

    protected function creationRequete()
    {
        $requete = $this->requete;

        foreach ($this->tris as $tri) {
            if ($tri['actif']) {
                $requete->orderBy(isset($tri['colonne']) ? $tri['colonne'] : $tri['nom'], $tri['ordre']);
            }
        }

        foreach ($this->filtres as $filtre) {
            if ($filtre['valeur'] !== null) {
                $operateur = isset($filtre['operateur']) ? $filtre['operateur'] : '=';
                if ($operateur == 'like') {
                    // Pas de posibilité de faire tous les like
                    $filtre['valeur'] = '%'.$filtre['valeur'].'%';
                }
                if (is_array($filtre['colonnes'])) {
                    $requete->where(function($query) use ($filtre, $operateur)
                    {
                        foreach ($filtre['colonnes'] as $colonne) {
                            $query->orWhere($colonne, $operateur, $filtre['valeur']);
                        }
                    });
                } else {
                    $requete->where($filtre['colonnes'], $operateur, $filtre['valeur']);
                }
            }
        }


        return $requete;
    }

    public function rechercherLignes()
    {
        if ($this->lignes === null) {
            $requete = $this->creationRequete();
            $this->lignes = $requete->paginate($this->nb_lignes)->appends($this->parametres);
        }

        return $this->lignes;
    }

    public function pagination()
    {
        return $this->lignes->links();
    }

    /**
    * Nombre de résultat de la liste
    * @return int $count
    */
    public function count()
    {
        return $this->lignes->getTotal();;
    }


    public function getUrl($parametres_a_exclure = array(), $page = false)
    {
        // Voir la method Paginator::getUrl()

        $parametres = $this->getParametres($parametres_a_exclure);

        if ($page) {
            $pages = array(
                $this->lignes->getFactory()->getPageName() => $this->getPageCourante(),
            );
            $parametres = array_merge($pages, $parametres);
        }

        return $this->lignes->getFactory()->getCurrentUrl().'?'.http_build_query($parametres, null, '&'); // TODO .$fragment
    }

    public function inputsHidden()
    {
        $html = '';

        foreach ($this->parametres['tri'] as $nom => $value) {
            $html .= '<input type="hidden" name="tri['.e($nom).']" value="'.e($value).'">';
        }
        if ($this->query_complementaires !== null) {
            foreach ($this->query_complementaires as $nom => $value) {
                $html .= '<input type="hidden" name="'.e($nom).'" value="'.e($value).'">';
            }
        }
        return $html;
    }

    public function lienTri($intitule_lien, $nom_tri, $ordre_defaut = null)
    {
        if (!isset($this->tris[$nom_tri])) {
            return false;
        }

        $tri = $this->tris[$nom_tri];

        $fleche = '';
        if ($tri['actif']) {
            $ordre = $tri['ordre'] == 'desc' ? 'asc' : 'desc';
            $fleche = '&nbsp;'.($tri['ordre'] == 'desc' ? '&#x21D3;' : '&#x21D1;');
        } else {
            $ordre = $ordre_defaut === null ? $tri['ordre'] : $ordre_defaut;
        }

        $ordre_intitule = trans('IpsumCore::liste.tri.'.$ordre);

        return '<a rel="nofollow" href="'.$this->getUrl(array('tri')).'&tri['.$nom_tri.']='.$ordre.'" '
        .'title="'.trans('IpsumCore::liste.tri.title', array('nom' => $nom_tri, 'ordre' => $ordre_intitule)).'">'
        .e($intitule_lien)
        .$fleche
        .'</a>';
    }

    public function getFiltreValeur($nom)
    {
        return isset($this->filtres[$nom]) ? $this->filtres[$nom]['valeur'] : false;
    }


    protected function getParametres($parametres_a_exclure = null)
    {
        $parametres = $this->parametres;

        if ($parametres_a_exclure !== null) {
            $parametres = $this->array_exclusion_recursive($parametres, $parametres_a_exclure);
        }

        return $parametres;
    }

    protected function array_exclusion_recursive (array $array, array $exclusion) {
        foreach ($array as $key => $value) {
            if (is_array($value) and isset($exclusion[$key])) {
                $array[$key] = $this->array_exclusion_recursive($value, $exclusion[$key]);
                if (empty($array[$key])) {
                    unset($array[$key]);
                }
            } elseif (in_array($key, $exclusion)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

}
