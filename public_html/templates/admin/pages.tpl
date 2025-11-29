{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-9 mt-5">
    <div class="card card-body">
        <h1>Pages</h1><a href="?action=add">Add new page</a>

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
                    {section name=page loop=$wcioShopAdminPages}
                    <tr>
                        <td>{$wcioShopAdminPages[page].name}</td>
                        <td>
                            <a href="/admin/wcio_pages.php?action=edit&id={$wcioShopAdminPages[page].id}">Edit</a> |
                            <a href="{$wcioShopAdminPages[page].url}" target="_blank">View</a> |
                            <a href="/admin/wcio_pages.php?action=delete&id={$wcioShopAdminPages[page].id}" 
                                onclick="return confirm('Are you sure you want to delete this page?');">
                                Delete
                             </a>
                             
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