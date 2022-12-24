<?php namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
      #admin
      View::composer('admin.layout.default', 'App\Composers\Admin\AdminLayoutDefaultComposer');
      View::composer('admin.page.index', 'App\Composers\Admin\AdminPageComposer');
      View::composer('admin.page.edit', 'App\Composers\Admin\AdminPageComposer');

      /*
      View::composer('admin.layout.default', 'Kythera\Composers\Admin\Layout\DefaultComposer');
      View::composer('admin.page.index', 'Kythera\Composers\Admin\Page\DefaultComposer');
      View::composer('admin.page.edit', 'Kythera\Composers\Admin\Page\DefaultComposer');
      */
      /*
      View::composer('admin.page.index', 'AdminPageIndexComposer');
      View::composer('admin.page.edit', 'AdminPageEditComposer');
      */

      #site
      View::composer('site.layout.default', 'App\Composers\PageComposer');
      View::composer('site.page.default', 'App\Composers\PageComposer');

      View::composer('site.page.home.index', 'App\Composers\HomeComposer');
      View::composer('site.page.home.blocks.keys', 'App\Composers\HomeComposer');
      View::composer('site.page.home.blocks.keys-small', 'App\Composers\HomeComposer');
      View::composer('site.page.home.blocks.posts', 'App\Composers\HomeComposer');
      View::composer('site.page.footer.photos', 'App\Composers\HomeComposer');
      View::composer('site.page.footer.photos-2', 'App\Composers\HomeComposer');
      View::composer('site.page.footer.posts', 'App\Composers\HomeComposer');
      View::composer('site.page.footer.social', 'App\Composers\HomeComposer');


      View::composer('site.document.text.index', 'App\Composers\DocumentTextComposer');
      View::composer('site.document.text.view', 'App\Composers\DocumentTextComposer');
      View::composer('site.document.image.index', 'App\Composers\DocumentImageComposer');
      View::composer('site.document.image.view', 'App\Composers\DocumentImageComposer');
      View::composer('site.document.audio.index', 'App\Composers\DocumentAudioComposer');
      View::composer('site.document.audio.view', 'App\Composers\DocumentAudioComposer');
      View::composer('site.document.video.index', 'App\Composers\DocumentVideoComposer');
      View::composer('site.document.video.view', 'App\Composers\DocumentVideoComposer');
      View::composer('site.document.guestbook.index', 'App\Composers\DocumentTextComposer');
      View::composer('site.document.guestbook.edit', 'App\Composers\DocumentTextComposer');
      View::composer('site.document.guestbook.contact', 'App\Composers\DocumentTextComposer');
      View::composer('site.document.message.index', 'App\Composers\DocumentTextComposer');
      View::composer('site.document.quoted.index', 'App\Composers\DocumentTextComposer');
      View::composer('site.document.quoted.view', 'App\Composers\DocumentTextComposer');

      View::composer('site.document.create', 'App\Composers\EntryComposer');
      //View::composer('site.document.details', 'EntryComposer');
      //View::composer('site.document.details2', 'EntryComposer');
      View::composer('site.document.next', 'App\Composers\EntryComposer');
      View::composer('site.document.edit', 'App\Composers\EntryComposer');
      View::composer('site.document.next2', 'App\Composers\EntryComposer');
      View::composer('site.document.quoted.edit', 'App\Composers\EntryComposer');
      //View::composer('site.document.image.edit', 'EntryComposer');

      View::composer('site.document.multi.create', 'App\Composers\EntryComposer');
      View::composer('site.document.multi.next', 'App\Composers\EntryComposer');
      View::composer('site.document.multi.edit', 'App\Composers\EntryComposer');

      View::composer('site.document.text.blocks.sidebar.posts', 'App\Composers\SidebarComposer');
      View::composer('site.document.text.blocks.sidebar.media', 'App\Composers\SidebarComposer');
      View::composer('site.document.text.blocks.sidebar.tourist', 'App\Composers\SidebarComposer');
      View::composer('site.document.text.blocks.sidebar.message', 'App\Composers\SidebarComposer');
      View::composer('site.document.text.blocks.sidebar.natural', 'App\Composers\SidebarComposer');


      #pages
      //fixme: can we do this programatically somewhere??
      View::composer('site.people.names.index', 'App\Composers\SitePageComposer');
      View::composer('site.people.names.view', 'App\Composers\PeopleNamesComposer');
      View::composer('site.page.personal.index', 'App\Composers\PageComposer');
      View::composer('site.page.search.index', 'App\Composers\PageComposer');


      View::composer('site.info.index', 'App\Composers\SiteInfoIndexComposer');
      View::composer('site.layout.default', 'App\Composers\SiteLayoutDefaultComposer');
      View::composer('site.index.dynamic', 'App\Composers\SiteIndexDynamicComposer');
      View::composer('site.layout.menu', 'App\Composers\SiteLayoutMenuComposer');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
