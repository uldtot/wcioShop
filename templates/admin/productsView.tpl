{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-9 mt-5">
    <div class="card card-body">
        <h1>Edit product</h1>

<div>

   <!-- Button to open modal upload  -->
   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#primaryImageModal">
    Select images and files
</button>


</div>       

        <form method="post" action="/admin/wcio_products.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="{$smarty.get.id|default:0}">

            <label class="active"></label>

            <input type="checkbox" name="active" data-toggle="toggle" {if $productData.active=="1" }checked{/if}> Active<br>

            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{$productData.name}"><br>


            <label for="permalink">Permalink</label>
            <p>If you do not fill this, it will be set automatically</p>
            <input type="text" name="permalink" class="form-control" value="{$productData.url}"
                placeholder="Example: large-rubber-ducky"><br>


            <label for="name">Excerpt</label>
            <textarea name="excerpt" rows="4" cols="50"
                class="form-control">{$productData.excerpt|default:''}</textarea><br>


            <label for="name">Full description</label>
            <textarea name="fullDescription" rows="4" cols="50"
                class="form-control">{$productData.fullDescription|default:''}</textarea><br>



            <label for="name">SKU</label>
            <input type="text" name="sku" class="form-control" value="{$productData.sku|default:''}"
                placeholder="Example: 5901234123457"><br>


                <label for="name">Weight ({$settingStoreWeightType})</label>
                <input type="text" name="weight" class="form-control" value="{$productData.weight|default:''}"><br>

            <div style="border: 1px solid #cecece;padding: 20px;margin-bottom:20px;">
                <h2>Prices</h2>
                <p>Price is the normal price of your product. If your product is on sale, enter something in the "Sale
                    price" field. If this field is empty or 0, it will use the normal price.</p>
                {foreach from=$wcioShopAdminCurrenciesArray key=k item=i}
                {assign var=price value="price_{$i}"}
                {assign var=salePrice value="salePrice_{$i}"}

                <div class="row">
                    <div class="col-md-6 mt-6">
                        {$i} Price <input type="number" name="price_{$i}" class="form-control"
                            value="{$productData.{$price}|default:'0'}"><br>
                    </div>
                    <div class="col-md-6 mt-6">
                        {$i} Sale price <input type="number" name="salePrice_{$i}" class="form-control"
                            value="{$productData.{$salePrice}|default:''}"><br>
                    </div>
                </div>
                {/foreach}

            </div>


            <div style="border: 1px solid #cecece;padding: 20px;margin-bottom:20px;">
                <h2>Categories</h2>

                {foreach from=$wcioShopAdminCategoriesArray key=k item=i}
                <input type="checkbox" name="productCategories[]" data-toggle="toggle" {if
                    $wcioShopAdminCategoriesArray.$k.productInCategory=="1" }checked{/if} value="{$i.id}"> {$i.name}<br>
                {/foreach}

            </div>

            <div>
                <h3>SEO data</h3>
                <input type="hidden" name="save" value="1">
                <!--Table-->
                <table id="tablePreview" class="table">

                    <!--Table body-->
                    <tbody>

                        <tr>

                            <td style="width: 50%"><b>SEOtitle</b><br>
                                <p>Write a good title</p>
                            </td>
                            <td>
                                <input type="text" name="SEOtitle" class="form-control" value="{$productData.SEOtitle}">
                            </td>
                        </tr>
                        <tr>

                            <td style="width: 50%"><b>SEOkeywords</b><br>
                                <p>Give some keywords (Comma seperated)</p>
                            </td>
                            <td>
                                <input type="text" name="SEOkeywords" class="form-control"
                                    value="{$productData.SEOkeywords}">
                            </td>
                        </tr>
                        <tr>

                            <td style="width: 50%"><b>SEOdescription</b><br>
                                <p>Make a description about this</p>
                            </td>
                            <td>
                                <textarea name="SEOdescription" class="form-control"
                                    rows="10">{$productData.SEOdescription}</textarea>
                            </td>
                        </tr>
                        <tr>

                            <td style="width: 50%"><b>SEOnoIndex</b><br>
                                <p>Activate noindex?
                                </p>
                            </td>
                            <td>
                                <input type="checkbox" name="SEOnoIndex" class="form-control" {if
                                    $productData.SEOnoIndex==1}checked{/if}>
                            </td>
                        </tr>



                    </tbody>
                    <!--Table body-->
                </table>
                <!--Table-->

             
            </div>



            <button type="submit" class="btn btn-primary">Save</button>
        </form>




    </div>
    
    
    
    
<!-- Primary Image Modal -->
<div class="modal fade" id="primaryImageModal" tabindex="-1" role="dialog" aria-labelledby="primaryImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="primaryImageModalLabel">Select files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {include file='mediaSelect.tpl'}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <!-- JavaScript til filtrering og modal -->
<script>
    document.getElementById('fileSearch').addEventListener('input', function () {
        var searchQuery = this.value.toLowerCase();
        var fileRows = document.querySelectorAll('.file-row');

        fileRows.forEach(function(row) {
            var fileName = row.querySelector('td').textContent.toLowerCase();
            if (fileName.includes(searchQuery)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Event listener for knapperne
document.querySelectorAll('.make-primary-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var fileName = this.getAttribute('data-file');
        var currentFolder = this.getAttribute('data-currentFolder');
        var action = this.getAttribute('data-action');

        // Send AJAX request to /admin/inc/mediaSelect.php
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/inc/mediaSelect.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Skift tekst p책 knappen for at vise 'Remove as Primary' eller 'Make Primary'
                    if (action === 'makePrimary') {
                        button.textContent = 'Remove as Primary';
                        button.setAttribute('data-action', 'removePrimary');
                    } else {
                        button.textContent = 'Make Primary';
                        button.setAttribute('data-action', 'makePrimary');
                    }
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            alert('Request failed');
        };

        xhr.send('fileName=' + encodeURIComponent(fileName) + '&currentFolder=' + encodeURIComponent(currentFolder) + '&action=' + encodeURIComponent(action));
    });
});

// Event listener for knapperne til 'Make Gallery'
document.querySelectorAll('.make-gallery-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var fileName = this.getAttribute('data-file');
        var currentFolder = this.getAttribute('data-currentFolder');
        var action = this.getAttribute('data-action');

        // Send AJAX request to /admin/inc/mediaSelect.php
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/inc/mediaSelect.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Skift tekst p책 knappen for at vise 'Remove from Gallery' eller 'Make Gallery'
                    if (action === 'makeGallery') {
                        button.textContent = 'Remove from Gallery';
                        button.setAttribute('data-action', 'removeGallery');
                    } else {
                        button.textContent = 'Make Gallery';
                        button.setAttribute('data-action', 'makeGallery');
                    }
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            alert('Request failed');
        };

        xhr.send('fileName=' + encodeURIComponent(fileName) + '&currentFolder=' + encodeURIComponent(currentFolder) + '&action=' + encodeURIComponent(action));
    });
});

// Event listener for knapperne til 'Make Additional'
document.querySelectorAll('.make-additional-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var fileName = this.getAttribute('data-file');
        var currentFolder = this.getAttribute('data-currentFolder');
        var action = this.getAttribute('data-action');

        // Send AJAX request to /admin/inc/mediaSelect.php
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/inc/mediaSelect.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Skift tekst p책 knappen for at vise 'Remove from Additional' eller 'Make Additional'
                    if (action === 'makeAdditional') {
                        button.textContent = 'Remove from Additional';
                        button.setAttribute('data-action', 'removeAdditional');
                    } else {
                        button.textContent = 'Make Additional';
                        button.setAttribute('data-action', 'makeAdditional');
                    }
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            alert('Request failed');
        };

        xhr.send('fileName=' + encodeURIComponent(fileName) + '&currentFolder=' + encodeURIComponent(currentFolder) + '&action=' + encodeURIComponent(action));
    });
});


</script>
    
    {include file='template-parts/footer.tpl'}
