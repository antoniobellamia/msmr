</li>
                </ul>

            </div>
        </div>
        </div>

    </nav>

    <script>
        var mediaQuery = window.matchMedia("(max-width: 768px)");
var div1 = document.getElementById("menuUno");
var div2 = document.getElementById("menuDue");
let clickedOnce = false;


function handleMediaQueryChange(e) {
    clickedOnce = false;

    if (e.matches) {

        div1.classList.remove("pure-menu-horizontal");
        div2.classList.remove("pure-menu-horizontal");
        menuOff();

    } else {
        div1.classList.add("pure-menu-horizontal");
        div2.classList.add("pure-menu-horizontal");
        menuOn();

    }
}

mediaQuery.addEventListener('change', handleMediaQueryChange);

handleMediaQueryChange(mediaQuery);



function openmenu() {
    clickedOnce = !clickedOnce;

    if (!clickedOnce) {
        menuOff();
    } else {
        menuOn();
    }
}

function menuOff() {
    document.getElementById("menuIcon").classList.add("fa-bars");
    document.getElementById("menuIcon").classList.remove("fa-x");
    document.getElementById("menu-elem").style.visibility = "hidden";
    document.getElementById("menu-elem").style.display = "none";
    document.getElementById("menu-elem2").style.visibility = "hidden";
    document.getElementById("menu-elem2").style.display = "none";
}

function menuOn() {
    document.getElementById("menuIcon").classList.remove("fa-bars");
    document.getElementById("menuIcon").classList.add("fa-x");
    document.getElementById("menu-elem").style.visibility = "visible";
    document.getElementById("menu-elem").style.display = "initial";
    document.getElementById("menu-elem2").style.visibility = "visible";
    document.getElementById("menu-elem2").style.display = "initial";
}
    </script>