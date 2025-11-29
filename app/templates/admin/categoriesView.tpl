{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}




<div class="col-md-9 mt-5">
      <form method="post" action="/admin/wcio_categories.php">
            <div class="card card-body">
                  <h1>Edit category</h2>

                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="{$categoryData.id}">


                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{$categoryData.name}"><br>

                        <label for="permalink">Permalink</label>
                        <p>If you do not fill this, it will be set automatically based on the category name</p>
                        <input type="text" name="permalink" class="form-control" value="{$categoryData.url}"
                              placeholder="Example: /large-rubber-ducky/"><br>

                        <label for="name">Description</label>
                        <p><strong>Tip: use {literal}&lt;!--split--&gt;{/literal} to split your text into several parts
                                    for use in your template. Default template uses this for top and bottom
                                    description.</strong></p>
                        <textarea name="fullDescription" rows="10" cols="50"
                              class="form-control">{$categoryData.description}</textarea>
                        <br>




                        <button type="submit" class="btn btn-primary">Update</button>


            </div>




            <div class="card card-body mt-3">
                  <h3>SEO data</h3>
                        <!--Table-->
                        <table id="tablePreview" class="table">

                              <!--Table body-->
                              <tbody>

                                    <tr>

                                          <td style="width: 50%"><b>SEOtitle</b><br>
                                                <p>Write a good title</p>
                                          </td>
                                          <td>
                                                <input type="text" name="SEOtitle" class="form-control" value="{$categoryData.SEOtitle}">
                                          </td>
                                    </tr>
                                    <tr>

                                          <td style="width: 50%"><b>SEOkeywords</b><br>
                                                <p>Give some keywords (Comma seperated)</p>
                                          </td>
                                          <td>
                                                <input type="text" name="SEOkeywords" class="form-control" value="{$categoryData.SEOkeywords}">
                                          </td>
                                    </tr>
                                    <tr>

                                          <td style="width: 50%"><b>SEOdescription</b><br>
                                                <p>Make a description about this</p>
                                          </td>
                                          <td>
                                                <textarea name="SEOdescription" class="form-control" rows="10">{$categoryData.SEOdescription}</textarea>
                                          </td>
                                    </tr>
                                    <tr>

                                          <td style="width: 50%"><b>SEOnoIndex</b><br>
                                                <p>Activate noindex?
                                                </p>
                                          </td>
                                          <td>
                                                <input type="checkbox" name="SEOnoIndex" class="form-control" {if $categoryData.SEOnoIndex == 1}checked{/if}>
                                          </td>
                                    </tr>



                              </tbody>
                              <!--Table body-->
                        </table>
                        <!--Table-->

                        <button type="submit" class="btn btn-primary">Update</button>

                 
            </div>

      </form>


</div> <!-- col.// -->

{include file='template-parts/footer.tpl'}