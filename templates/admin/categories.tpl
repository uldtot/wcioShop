{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-9 mt-5">
    <div class="card card-body">
        <h1>Categories</h1><a href="?action=add">Add new category</a>

            <!--Table-->
            <table id="tablePreview" class="table">
                <!--Table head-->
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>View</th>
                    </tr>
                </thead>
                <!--Table head-->
                <!--Table body-->
                <tbody>
                    {section name=category loop=$wcioShopAdminCategories}
                    <tr>
                        <td>{$wcioShopAdminCategories[category].name}</td>
                        <td>
                            <a href="/admin/wcio_categories.php?action=edit&id={$wcioShopAdminCategories[category].prdid}">Edit</a> |
                            <a href="{$wcioShopAdminCategories[category].url}" target="_blank">View</a>
                        </td>
                    </tr>
                    {/section}

                </tbody>
                <!--Table body-->
            </table>
            <!--Table-->


    </div>
</div> <!-- col.// -->
{include file='template-parts/footer.tpl'}
