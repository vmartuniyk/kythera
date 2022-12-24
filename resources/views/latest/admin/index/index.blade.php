@extends("admin.layout.default")

@section("content")
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
                  <br/><a target="_blank" href="/xhtml/content.html">Default</a>
                  <br/><a target="_blank" href="/xhtml/gravestones.html">Sidebar</a>
                  
              </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary">
              <div class="panel-heading">Information</div>
              <div class="panel-body">
                  <a target="issues" href="/issues">Issues Tracker</a>
                  <br/><a href="/downloads/database-0.1.ods">Database layout (OO Calc)</a>
                  <br/><a href="/downloads/tasks-0.2.ods">Tasklist (OO Calc)</a>
              </div>
            </div>
        </div>
        <div class="col-sm-4">
        </div>
    </div>
    
    <div class="panel panel-primary">
      <div class="panel-heading">KFN-2.0 Log</div>
      <div class="panel-body">
          
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
    
@stop