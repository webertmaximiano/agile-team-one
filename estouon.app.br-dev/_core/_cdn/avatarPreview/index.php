<link rel="stylesheet" href="filepreview.css">

<div class="file-preview">

  <div id="image-preview" style='background: url("") no-repeat center center; background-size: auto 102%;'>
    <label for="image-upload" id="image-label">Enviar imagem</label>
    <input type="file" name="avatar" id="image-upload"/>
  </div>
  <span class="explain">Selecione sua foto de perfil clicando no campo ou</br/> arrastando o arquivo!</span>

</div>

<script src="jquery.js"></script>
<script src="jquery.uploadPreview.min.js"></script>

<script>
  $(document).ready(function() {
    $.uploadPreview({
      input_field: "#image-upload",
      preview_box: "#image-preview",
      label_field: "#image-label",
      label_default: "Envie ou arraste",
      label_selected: "Trocar imagem",
      no_label: false
    });
  });
</script>

