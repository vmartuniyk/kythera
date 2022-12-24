<!-- Depricated in Bootstrap 3.0+: restore 2.0 behaviour -->
<style>
.dropdown-submenu {
    position:relative;
}
.dropdown-submenu>.dropdown-menu {
    top:0;
    left:100%;
    margin-top:-6px;
    margin-left:-1px;
    -webkit-border-radius:0 6px 6px 6px;
    -moz-border-radius:0 6px 6px 6px;
    border-radius:0 6px 6px 6px;
}
.dropdown-submenu:hover>.dropdown-menu {
    display:block;
}
.dropdown-submenu>a:after {
    display:block;
    content:" ";
    float:right;
    width:0;
    height:0;
    border-color:transparent;
    border-style:solid;
    border-width:5px 0 5px 5px;
    border-left-color:#cccccc;
    margin-top:5px;
    margin-right:-10px;
}
.dropdown-submenu:hover>a:after {
    border-left-color:#ffffff;
}
.dropdown-submenu.pull-left {
    float:none;
}
</style>

<!-- import -->
<li class="dropdown" style="display:{!!Config::get('app.debug') ? 'block' : 'none'!!}">
    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Documents <span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">
        <li class="dropdown-submenu">
            <a href="#">People</a>
            <ul class="dropdown-menu">
                <li><a href="/en/admin/migrate/names">Names</a></li>
                <li><a href="/en/admin/migrate/nicknames">Nicknames</a></li>
                <li><a href="/en/admin/migrate/surnamebook">Surname Book</a></li>
                <li><a href="/en/admin/migrate/lifestories">Life Stories</a></li>
                <li><a href="/en/admin/migrate/famous-people">Famous People</a></li>
                <li><a href="/en/admin/migrate/obituaries">Obituaries</a></li>
            </ul>
        </li>
        <li class="dropdown-submenu">
            <a href="#">Gravestones</a>
            <ul class="dropdown-menu">
                <li><a href="/en/admin/migrate/gravestones">Gravestones</a></li>
                <li><a href="/en/admin/migrate/gravestones-karavas">Karavas</a></li>
                <li><a href="/en/admin/migrate/gravestones-potamos">Potamos</a></li>
            </ul>
        </li>
        <li class="dropdown-submenu">
            <a href="#">History</a>
            <ul class="dropdown-menu">
                <li><a href="/en/admin/migrate/oral-history">Oral History</a></li>
            </ul>
        </li>
        <li class="dropdown-submenu">
            <a href="#">Photography Island</a>
            <ul class="dropdown-menu">
                <li><a href="/en/admin/migrate/architecture">Architecture</a></li>
                <li><a href="/en/admin/migrate/social-life">Social Life</a></li>
            </ul>
        </li>
        <li class="dropdown-submenu">
            <a href="#">Real Estate</a>
            <ul class="dropdown-menu">
                <li><a href="/en/admin/migrate/real-estate-how">How to Post an Entry</a></li>
                <li><a href="/en/admin/migrate/real-estate-houses">House for Sale</a></li>
            </ul>
        </li>
        <li class="dropdown-submenu">
            <a href="#">Audio/Video</a>
            <ul class="dropdown-menu">
                <li><a href="/en/admin/migrate/kytherian-music">Kytherian Music</a></li>
                <li><a href="/en/admin/migrate/sounds-of-nature">Sounds of Nature</a></li>
            </ul>
        </li>
        <li>
            <a href="/en/admin/migrate/guestbook">Guestbook</a>
            <a href="/en/admin/migrate/board">Message Board</a>
            <a href="/en/admin/migrate/news-archive">News Archive</a>
        </li>
        <li xstyle="display:none" class="divider"></li>
        <li xstyle="display:none"><a href="/en/admin/migrate/navigation">Navigation</a>
        <li xstyle="display:none"><a href="/en/admin/migrate/category">Category</a>
    </ul>
</li>
