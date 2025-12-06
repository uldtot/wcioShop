{$maintenanceMode}

<header class="section-header">

<section class="header-main border-bottom">
    <div class="container">

        <!-- ROW 1: LOGO – SEARCH DESKTOP – MENU/CART -->
        <div class="row align-items-center">

            <!-- LOGO -->
            <div class="col-6 col-lg-4 mb-2">
                <div class="p-2 d-flex align-items-center">
                    <a href="/" class="d-flex flex-column">
                        <h3 class="storeName mb-0">{$settingStoreName}</h3>
                        <span class="storeSlogan small">{$settingStoreSlogan}</span>
                    </a>
                </div>
            </div>

            <!-- SEARCH DESKTOP -->
            <div class="col-lg-4 d-none d-lg-block mb-2">
                <div class="p-2 search-wrapper">
                    <form class="search position-relative">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="{_('Search')}" onkeyup="showResult(this)">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="livesearch-box"></div>
                </div>
            </div>

            <!-- MENU + CART -->
            <div class="col-6 col-lg-4 mb-2">
                <div class="p-2 d-flex justify-content-end align-items-center">

                    {if isset($settingStoreCatalogMode)}
                    {if $settingStoreCatalogMode != "1"}
                    <a href="/cart/" class="icon icon-sm rounded-circle border position-relative mr-3">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="badge badge-pill badge-danger notify position-absolute" style="top:-8px; right:-8px;">
                            {nocache}{if isset($headerCart.numberOfItems)}{if $headerCart.numberOfItems!="0"}{$headerCart.numberOfItems}{/if}{/if}{/nocache}
                        </span>
                    </a>
                    {/if}
                    {/if}

                    <button class="navbar-toggler d-lg-none" type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                </div>
            </div>

        </div> <!-- END ROW 1 -->


        <!--  MOBILE MENU RIGHT UNDER BUTTON  -->
        <div id="mobile_nav_container" class="mobile-nav-container d-lg-none">
            <div id="mobile_nav" class="mobile-nav">
                <ul class="navbar-nav">
                    {section name=category loop=$navigationStyleCategories}
                        {if $navigationStyleCategories[category].url != ""}
                        <li class="nav-item categoryId-{$navigationStyleCategories[category].id}">
                            <a class="nav-link" href="{$navigationStyleCategories[category].url}">
                                {$navigationStyleCategories[category].name}
                            </a>
                        </li>
                        {/if}
                    {/section}
                </ul>
            </div>
        </div>

        <!-- SEARCH MOBILE -->
        <div class="row d-lg-none">
            <div class="col-12 mb-0">
                <div class="search-wrapper">
                    <form class="search position-relative">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="{_('Search')}" onkeyup="showResult(this)">
                            
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="livesearch-box"></div>
                </div>
            </div>
        </div>

    </div>
</section>


<!--  DESKTOP NAVIGATION MENU  -->
<nav class="navbar navbar-main navbar-expand-lg navbar-light d-none d-lg-block">
  <div class="container">
    <div class="collapse navbar-collapse show" id="main_nav_desktop">
      <ul class="navbar-nav">
        {section name=category loop=$navigationStyleCategories}
            {if $navigationStyleCategories[category].url != ""}
            <li class="nav-item categoryId-{$navigationStyleCategories[category].id}">
                <a class="nav-link" href="{$navigationStyleCategories[category].url}">
                    {$navigationStyleCategories[category].name}
                </a>
            </li>
            {/if}
        {/section}
      </ul>
    </div>
  </div>
</nav>

</header>



<style>

/* Livesearch styling */
.livesearch-box {
    display: none;
    position: absolute;
    top: 42px;
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 8px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.search-wrapper {
    position: relative;
}

/* Mobile menu animation */
.mobile-nav-container {
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height 0.35s ease, opacity 0.25s ease;
}

.mobile-nav-container.show {
    max-height: 400px;
    opacity: 1;
}

.mobile-nav {
    background: #fff;
    padding: 10px 0;
    border-radius: 5px;
}

.navbar-toggler {
    border: none;
}


/* Ensure image container never exceeds 33% */
.livesearch-item .item-image {
    flex: 0 0 33%;   /* max 33% width */
    max-width: 33%;
}

/* Make image responsive within container */
.livesearch-item img {
    width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: 4px;
}

/* Optional: keep layout clean */
.livesearch-item .item-info {
    flex: 1;
    min-width: 0; /* IMPORTANT so long titles wrap properly */
}


@media (max-width: 991px) {
    .section-header .search {
        margin-top: 0;
        margin-bottom: 0;
    }
}
</style>



<script>
/* Mobile menu toggle */
document.querySelector('.navbar-toggler').addEventListener('click', function(){
    document.getElementById('mobile_nav_container').classList.toggle('show');
});
</script>



<script>
/* Shared livesearch for BOTH desktop + mobile */
let searchTimeout = null;

function showResult(inputEl) {

    const wrapper = inputEl.closest(".search-wrapper");
    const resultBox = wrapper.querySelector(".livesearch-box");
    const str = inputEl.value;

    clearTimeout(searchTimeout);

    if (str.length <= 2) {
        resultBox.style.display = "none";
        resultBox.innerHTML = "";
        return;
    }

    searchTimeout = setTimeout(() => {

        resultBox.style.display = "block";
        resultBox.innerHTML = "<div style='padding:5px;'>Søger...</div>";

        fetch("/liveSearch/?q=" + encodeURIComponent(str))
            .then(res => res.text())
            .then(data => {
                if (data.trim() !== "") {
                    resultBox.innerHTML = data;
                    resultBox.style.display = "block";
                } else {
                    resultBox.style.display = "none";
                    resultBox.innerHTML = "";
                }
            })
            .catch(err => {
                resultBox.innerHTML = "<div style='color:red;'>Fejl i søgning</div>";
                resultBox.style.display = "block";
                console.error(err);
            });

    }, 200);
}
</script>
