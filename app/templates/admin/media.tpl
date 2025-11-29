{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}

<div class="col-md-9 mt-5">
    <div class="card">
        <div class="card-header">
            <h5>File Manager</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Venstre side - Mapper -->
                <div class="col-md-3">
                    <div class="list-group">
                        <!-- Vis den nuværende mappe -->
                        <a href="wcio_media.php?folder={$parentFolder}"
                            class="list-group-item list-group-item-action active">
                            {if isset($currentFolder)}{$parentFolder}{if $parentFolder == ""}Root{/if}{else}Root{/if}
                        </a>
                        {foreach from=$filesAndFolders.folders item=folder}
                        <!-- Vis mapper med ikoner, med den fulde sti i URL'en -->
                        <a href="wcio_media.php?folder={$currentFolder}/{$folder}"
                            class="list-group-item list-group-item-action {if $currentFolder == $folder}active{/if}">
                            <i class="fas fa-folder"></i> {$folder}
                        </a>
                        {/foreach}
                    </div>
                </div>

                <!-- Højre side - Filer i den valgte mappe -->
                <div class="col-md-9">
                    {if $currentFolder|startswith:'/uploads'}<!-- Vis mapper først --> <!-- Create Directory knap -->
                    <button class="btn btn-primary mb-3" type="button" data-toggle="collapse"
                        data-target="#createFolderForm" aria-expanded="false" aria-controls="createFolderForm">
                        Create directory
                    </button>

                    <!-- Upload file knap -->
                    <button class="btn btn-primary mb-3" type="button" data-toggle="collapse"
                        data-target="#uploadFileForm" aria-expanded="false" aria-controls="uploadFileForm">
                        Upload New File
                    </button>
                    {/if}
                    <!-- Skjult formularer under knapperne -->
                    <div class="collapse mt-3" id="createFolderForm">
                        <form action="inc/wcio_media_create_folder.php" method="POST" class="mb-3">
                            <input type="hidden" name="folderPath" value="{$currentFolder}">
                            <div class="form-group">
                                <input type="text" class="form-control" name="folderName" placeholder="Folder Name"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Directory</button>
                        </form>
                    </div>

                    <div class="collapse mt-3" id="uploadFileForm">
                        <form action="inc/wcio_media_upload.php" method="POST" enctype="multipart/form-data"
                            class="mb-3">
                            <input type="hidden" name="folderPath" value="{$currentFolder}">
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="file" multiple required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>

                    <!-- Filer i den valgte mappe -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6>Files in {if isset($currentFolder)}{$currentFolder}{else}Root{/if}</h6>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Size</th>
                                        <th width="40%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {if $currentFolder|startswith:'/uploads'}<!-- Vis mapper først -->
                                    {foreach from=$filesAndFolders.folders item=folder}
                                    <tr>
                                        <td><i class="fas fa-folder"></i> {$folder}</td>
                                        <td></td>
                                        <td class="file-actions">
                                            <!-- Kun vis delete-knap hvis vi er i uploads -->
                                            {if $currentFolder|startswith:'/uploads'}
                                            <!-- Renaming knap for mapper -->
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#renameModal" data-file="{$folder}"
                                                data-currentFolder="{$currentFolder}">
                                                Rename Folder
                                            </button>


                                            <form action="inc/wcio_media_delete_file_or_folder.php" method="POST"
                                                style="display:inline-block;" onsubmit="return confirmDelete();">
                                                <input type="hidden" name="deleteFolder" value="1">
                                                <input type="hidden" name="filePath" value="{$folder}">
                                                <input type="hidden" name="currentFolder" value="{$currentFolder}">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete
                                                    Folder</button>
                                            </form>
                                            {/if}
                                        </td>
                                    </tr>
                                    {/foreach}

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    {/if}
                                    <!-- Vis filer -->
                                    {foreach from=$filesAndFolders.files item=file}
                                    <tr>
                                        <td>
                                            {assign var="extension" value=$file.name|explode:'.'}
                                            {assign var="fileExt" value=$extension|@count}

                                            <!-- Hvis der er en udvidelse -->
                                            {if $fileExt > 1}
                                            {assign var="fileExtension" value=$extension[$fileExt-1]}
                                            {else}
                                            {assign var="fileExtension" value="no_extension"}
                                            {/if}
                                           

                                            <!-- Vis ikoner afhængigt af filtypen -->
                                            {if $fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension ==
                                            'png'}
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

                                            <!-- Filnavn -->
                                            {if $fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension ==
                                            'png' || $fileExtension == 'pdf'}<a href="{$currentFolder}/{$file.name}"
                                                target="_blank">{/if}
                                                {$file.name}
                                                {if $fileExtension == 'jpg' || $fileExtension == 'jpeg' ||
                                                $fileExtension ==
                                                'png' || $fileExtension == 'pdf'}<< /a>{/if}
                                        </td>
                                        <td>{$file.size}</td>
                                        <td class="file-actions">

                                            <!-- Kun vis delete-knap hvis vi er i uploads -->
                                            {if $currentFolder|startswith:'/uploads'}

                                            <!-- Renaming knap -->
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#renameModal" data-file="{$file.name}"
                                                data-currentFolder="{$currentFolder}">Rename</button>


                                            <form action="inc/wcio_media_delete_file_or_folder.php" method="POST"
                                                style="display:inline-block;" onsubmit="return confirmDelete();">
                                                <input type="hidden" name="deleteFile" value="1">
                                                <input type="hidden" name="filePath" value="{$file.path}">
                                                <input type="hidden" name="currentFolder" value="{$currentFolder}">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                            {/if}
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
    </div>
</div>

{include file='template-parts/footer.tpl'}

<!-- Modal for renaming -->
<div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="renameModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renameModalLabel">Rename File or Folder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="inc/wcio_media_rename.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="currentFolder" id="renameCurrentFolder">
                    <input type="hidden" name="fileName" id="renameFileName">
                    <div class="form-group">
                        <label for="newName">New Name</label>
                        <input type="text" class="form-control" name="newName" id="newName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Rename</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for at sætte de nødvendige værdier i formularen -->
<script>
    $('#renameModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Knapen der åbnede modal
        var fileName = button.data('file'); // Filen eller mappen der skal omdøbes
        var currentFolder = button.data('currentfolder'); // Aktuel mappe

        // Sæt de nødvendige værdier i formularen
        var modal = $(this);
        modal.find('#renameFileName').val(fileName); // Sætter stien til filen eller mappen
        modal.find('#renameCurrentFolder').val(currentFolder); // Sætter den aktuelle mappe

        // Sæt den nuværende fil- eller mappe-navn i inputfeltet
        var currentName = fileName; // Hent kun navnet fra filens sti
        modal.find('#newName').val(currentName); // Sæt den nuværende navn i inputfeltet
    });



    function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
    }
</script>
<style>
    /* Hide the Name and Delete buttons by default */
    .file-actions button {
        display: none;
    }

    /* Show the buttons when the row is hovered */
    .table tbody tr:hover .file-actions button {
        display: inline-block;
    }
</style>
{include file='template-parts/footer.tpl'}