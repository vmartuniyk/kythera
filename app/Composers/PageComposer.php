<?php

namespace App\Composers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentPerson;
use Kythera\Models\PageEntity;
use Kythera\Models\Person;
use Kythera\Router\Router;

/**
 * @author virgilm
 *
 */
class PageComposer extends Composer
{

    /**
     * @var Router
     */
    protected $router;


    public function __construct(\Kythera\Router\Facades\Router $router)
    {
        $this->router = $router;
    }


    public function compose($view)
    {
        parent::compose($view);

        switch ($name = $view->getName()) {
            case 'site.layout.default':
                //var endpoint = "{{ Router::getPageByID(113)->path }}";
                $endpoint = $this->getPageByID(PageEntity::PAGE_ADVANCED_SEARCH)->path;
                $view->with('endpoint', $endpoint);
                break;
            case 'site.page.default':
                $categories = [];
                if ($page = $this->getCurrentPage()) {
                    $categories = $page->children->all();
                }
                $view->with('categories', $categories);
                break;
            case 'site.page.personal.index':
                //show user categories
                $categories = DocumentEntity::getUserEntries(Auth::user(), [], true);
                //$categories = DocumentEntity::getUserEntries(Auth::user());
                $c = count($categories);
                $n = 0;
                foreach ($categories as $i => $category) {
                    if ($page = $this->getPageByID($category->page_id)) {
                        $category->category_uri = $page->path;
                    }
                    $n += $category->n;
                }
                $view->with('categories', $categories);
                $view->with('cat_stat', sprintf('%d posts in %d categories', $n, $c));

                //show user comments
                $comments = Comment::getUserComments(Auth::user(), [], true);
                $c = count($comments);
                $n = 0;
                foreach ($comments as $comment) {
                    /*if no page is found please check if category already is imported*/
                    if ($page = $this->getPageByID($comment->page_id)) {
                        $comment->category = $page;
                    }
                    $n += $comment->n;
                }

                $view->with('comments', $comments);
                $view->with('com_stat', sprintf('%d comments in %d categories', $n, $c));

                //family trees / persons
                $persons = Person::getByUser(Auth::user());
                $view->with('persons', $persons);
                $view->with('person_stat', sprintf('%d family trees', count($persons)));
                break;
            case 'site.page.search.index':
                $paginate_orders = [
                        1 => trans('locale.filter.newestfirst'),
                        2 => trans('locale.filter.newestlast'),
                        3 => trans('locale.filter.alphabetically'),
                        4 => trans('locale.filter.submitterfirst'),
                        5 => trans('locale.filter.submitterlast'),
                ];
                $paginate_orders = [
                        1 => trans('locale.filter.newestfirst'),
                        2 => trans('locale.filter.newestlast'),
                        3 => trans('locale.filter.alphabetically'),
                        4 => trans('locale.filter.submitter'),
                        5 => trans('locale.filter.relevance')
                ];

                $view->with('paginate_orders', $paginate_orders);
                $view->with('categories', $this->getCategories());
                $view->with('authors', $this->getAuthors());
                break;
            default:
        }
    }


    /**
     * Getter for $page
     *
     * @return Page
     */
    public function getCurrentPage()
    {
        $router = $this->router;
        return $router::getCurrentPage();//selected;
    }

    public function getPageById($id)
    {
        $router = $this->router;
        return $router::getPageByID($id);//selected;
    }

    private function getCategories()
    {
        return \Kythera\Router\Facades\Router::categories();
    }

    private function getAuthors()
    {
        return DB::table('users')->where('hide', 0)->orderBy('lastname')->get();
    }
}
