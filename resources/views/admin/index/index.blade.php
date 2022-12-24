@extends("admin.layout.default")

@section("content")
<div class="container">
    <h1>Control Panel</h1>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-primary">
              <div class="panel-heading">XHTML Templates</div>
              <div class="panel-body">
                  <a target="_blank" href="/xhtml/index.html">Homepage</a>
                  <br/><a target="_blank" href="/xhtml/login.html">Login / Register</a>
                  <br/><a target="_blank" href="/xhtml/about.html">About</a>
                  <br/><a target="_blank" href="/xhtml/contact.html">Contact</a>
                  <br/><a target="_blank" href="/xhtml/text.html">Text</a>
                  <br/><a target="_blank" href="/xhtml/list.html">Overview</a>
                  <br/><a target="_blank" href="/xhtml/entry.html">Entry</a>
              </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary">
              <div class="panel-heading">Information</div>
              <div class="panel-body">
                  <a target="issues" href="https://trello.com/b/29imo8U1/kythera-family-net">Trello issue tracker</a>
                  @if (App::isLocal())
                      <br/><a target="supervisor" href="http://supervisord.debian.mirror.virtec.org/">Supervisor</a>
                      <br/><a target="git" href="http://git.kythera-family.net/kfnv2.git/">Git</a>
                      <br/><a target="queue" href="http://beanstalkd2.debian.mirror.virtec.org/?server=localhost:11300">Beanstalk admin</a>
                      <br/><a target="cache" href="http://memcached.debian.mirror.virtec.org/stats.php">Memcached admin</a>
                      <br/><a href="/downloads/database.ods">Database layout (OO Calc)</a>
                      <br/><a href="/downloads/tasks.ods">Tasklist (OO Calc)</a>
                  @else
                      <br/><a target="supervisor" href="http://supervisor.kythera-family.net/">Supervisor</a>
                      <br/><a target="queue" href="http://queue.kythera-family.net/?server=localhost:11300&tube=kfn">Beanstalk admin</a>
                      <br/><a target="cache" href="http://cache.kythera-family.net/">Memcached admin</a>
                      <br/><a href="/downloads/database.ods">Database layout (OO Calc)</a>
                      <br/><a href="/downloads/tasks.ods">Tasklist (OO Calc)</a>
                  @endif
              </div>
            </div>
        </div>
        <div class="col-sm-4">
        </div>
    </div>

    @if (App::isLocal())
    <div class="panel panel-primary"  style="display:{!!Config::get('app.debug') ? 'block' : 'none'!!}">
      <div class="panel-heading">KFN-2.0 Log</div>
      <div class="panel-body">

          <h3>May 2015</h3>
          <ul class="log">
              <li>Search
              <p>- Autosuggest searchbox topmenu
              - Advanced search page
              - Related searches
              </p>
              </li>
              <li>Information
              <p>- Implemented test units
              </p>
              </li>
          </ul>

          <hr class="thin"/>
          <h3>April 2015</h3>
          <ul class="log">
              <li>Information
              <p>- Personal pages
              - Live visitor count
              </p>
              </li>
              <li>Comments
              <p>- Enabled entry comments
              - Email notification on add or change comment</p>
              </li>
              <li>Guestbook
              <p>- Add/edit entries users (without reCaptcha)
              - Add/edit entries guests with reCaptcha
              - Contact
              </p>
              </li>
          </ul>

          <hr class="thin"/>
          <h3>March 2015</h3>
          <ul class="log">
              <li>Information
              <p>- Setup Git repsitory
              - Bash scripts to sync DEV and LIVE content</p>
              </li>
              <li>Entries
              <p>- Single entry upload form
              - Multi entry upload form
              - Email notification on add or change entry</p>
              </li>
          </ul>

          <hr class="thin"/>
          <h3>February 2015</h3>
          <ul class="log">
              <li>Information
              <p>- Setup Git repsitory</p>
              </li>
              <li>Entries
              <p>- Proposal multifile entry upload form</p>
              </li>
          </ul>

          <hr class="thin"/>
          <h3>January 2015</h3>
          <ul class="log">
              <li>Users
              <p>- Permissions and roles
              - Enable access to foreign objects not owned by the user (family tree editing)
              - Enabled quick login form</p>
              </li>
              <li>Entries
              <p>- Uniform content form</p>
              </li>
          </ul>

          <hr class="thin"/>
          <h3>December 2014</h3>
          <ul class="log">
              <li>Import content
              <p>- Text/audio/video/image documents</p>
              </li>
          </ul>

          <hr class="thin"/>
          <h3>November 2014</h3>
          <ul class="log">
              <li>Dynamic pages
              <p>- SEO options
              - Bilingual content
              </p>
              </li>
          </ul>

          <hr class="thin"/>
          <h3>October 2014</h3>
          <ul class="log">
              <li>Basic multilanguage setup
              <p>- Greek and English(default)
              - Multilingual content is achieved by URI prefixing like /en/about and /gr/about ( /gr/Σχετικά με )</p>
              </li>
              <li>Migration of existing data
              <p>- Database layout
              - Tests to read original documents which can be accessed through Admin > Documents
              </p>
              </li>
              <li>User login/registration
              <p>- Original users are imported with hashed passwords and both original username or email can be used to login.
              - Registration requires email confirmation.
              - 'Remember me' and 'Lost password' functions.
              - To run import execute:
              <code>cd /var/www/vhosts/kythera-family.net/dev.kythera-family.net/</code><br/><code>php artisan kythera:import_users [optional: limit]</code>
              - User management can be accessed through <a href="/en/admin/users">Admin > Users</a></p>
              </li>
              <li>XHTML templates
              <p>- Homepage
              - Login / Register
              - About
              - Contact
              - Default
              - Sidebar
              </p>
              </li>
          </ul>
      </div>
    </div>
    @endif

</div>
@endsection
