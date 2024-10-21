{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-9 mt-5">

      <div class="card card-body">
            <h1>Edit category</h2>

                  <form method="post" action="/admin/wcio_categories.php">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="{$categoryData.id}">


                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{$categoryData.name}"><br>

                        <label for="permalink">Permalink</label>
                        <p>If you do not fill this, it will be set automatically based on the category name</p>
                        <input type="text" name="permalink" class="form-control" value="{$categoryData.url}"
                              placeholder="Example: /large-rubber-ducky/"><br>
      
                        <label for="name">Description</label>
                        <p><strong>Tip: use {literal}&lt;!--split--&gt;{/literal} to split your text into several parts for use in your template. Default template uses this for top and bottom description.</strong></p>
                        <textarea name="fullDescription" rows="10" cols="50" class="form-control">{$categoryData.description}</textarea>
                        <br>

                        <button type="submit" class="btn btn-primary">Update</button>
                  </form>

      </div>


</div> <!-- col.// -->

{include file='template-parts/footer.tpl'}
