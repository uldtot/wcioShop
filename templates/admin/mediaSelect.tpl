<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5>File selector</h5>
        </div>
        <div class="card-body">

            <!-- Søgefelt til filtrering -->
            <div class="form-group">
                <input type="text" id="fileSearch" class="form-control" placeholder="Søg efter filer...">
            </div>




            <!-- Liste af filer -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6>Alle Filer</h6>
                </div>
                <div class="card-body">
                    <table class="table" id="fileTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Size</th>
                                <th width="40%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$filesAndFolders.files item=file}
                            <tr class="file-row">
                                <td>
                                    <!-- Fil ikon afhængigt af filtypen -->
                                    {assign var="extension" value=$file.name|explode:'.'}
                                    {assign var="fileExt" value=$extension|@count}
                                    {if $fileExt > 1}
                                    {assign var="fileExtension" value=$extension[$fileExt-1]}
                                    {else}
                                    {assign var="fileExtension" value="no_extension"}
                                    {/if}

                                    {if $fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'png'}
                                    <i class="fas fa-image"></i>
                                    {elseif $fileExtension == 'pdf'}
                                    <i class="fas fa-file-pdf"></i>
                                    {elseif $fileExtension == 'txt'}
                                    <i class="fas fa-file-alt"></i>
                                    {elseif $fileExtension == 'zip'}
                                    <i class="fas fa-file-archive"></i>
                                    {else}
                                    <i class="fas fa-file"></i>
                                    {/if}

                                    <!-- Filnavn med link hvis relevant -->
                                    <a href="{$currentFolder}/{$file.name}" target="_blank">{$file.name}</a>
                                </td>
                                <td>{$file.size}</td>
                                <td class="file-actions">
                                    <!-- Make Primary-knap -->
                                    <button type="button" class="btn btn-primary btn-sm make-primary-btn"
                                        data-file="{$file.name}" data-currentFolder="{$currentFolder}"
                                        data-action="makePrimary" data-id="{$currentId}">
                                        Make Primary
                                    </button>
                                    <!-- Make Gallery-knap -->
                                    <button type="button" class="btn btn-secondary btn-sm make-gallery-btn"
                                        data-file="{$file.name}" data-currentFolder="{$currentFolder}"
                                        data-action="makeGallery">
                                        Make Gallery
                                    </button>

                                    <!-- Make -knap -->
                                    <button type="button" class="btn btn-secondary btn-sm make-additional-btn"
                                        data-file="{$file.name}" data-currentFolder="{$currentFolder}"
                                        data-action="makeAdditional">
                                        Make Additional
                                    </button>
                                </td>


                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>