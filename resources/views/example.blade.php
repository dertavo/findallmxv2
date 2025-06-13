<form id="fr">
@csrf
<input type="file" multiple id="fileInput">
<div id="fileList">
</div>
</form>
<button id="send">Enviar a Laravel</button>



<script>
  const fileInput = document.getElementById('fileInput');
  const fileList = document.getElementById('fileList');

  let selectedFiles = [];

  fileInput.addEventListener('change', () => {
    // Obtener la lista de archivos seleccionados
    const files = fileInput.files;

    // Agregar los archivos seleccionados a la lista de archivos previamente seleccionados
    for (let i = 0; i < files.length; i++) {
      selectedFiles.push(files[i]);
    }

    // Mostrar la lista de archivos seleccionados en la página
    fileList.innerHTML = '';
    for (let i = 0; i < selectedFiles.length; i++) {
      const file = selectedFiles[i];

      const li = document.createElement('li');
      li.innerHTML = `${file.name} <button data-index="${i}" class="removeButton">Eliminar</button>`;
      fileList.appendChild(li);
    }
  });


  fileList.addEventListener('click', event => {
    if (event.target.className === 'removeButton') {
      // Obtener el índice del archivo a eliminar
      const index = event.target.dataset.index;

      // Eliminar el archivo de la lista
      selectedFiles.splice(index, 1);

      // Actualizar la lista de archivos seleccionados en la página
      fileList.innerHTML = '';
      for (let i = 0; i < selectedFiles.length; i++) {
        const file = selectedFiles[i];

        const li = document.createElement('li');
        li.innerHTML = `${file.name} <button data-index="${i}" class="removeButton">Eliminar</button>`;
        fileList.appendChild(li);
      }
    }
  });

  fileList.addEventListener('click', event => {
    if (event.target.className === 'uploadButton') {

        // Crear un objeto FormData para enviar los archivos
        let formData = new FormData();
        // Agregar cada archivo seleccionado al objeto FormData
        for (let i = 0; i < selectedFiles.length; i++) {
          formData.append('files[]', selectedFiles[i]);
        }
        
        // Enviar la petición POST al servidor utilizando fetch
        fetch('/server/upload', {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
        });
    }
  });

  
  send = document.getElementById('send')
  send.addEventListener("click",event=>{
    // Crear un objeto FormData para enviar los archivos
    let formData = new FormData(document.getElementById('fr'));
        // Agregar cada archivo seleccionado al objeto FormData
        for (let i = 0; i < selectedFiles.length; i++) {
          formData.append('files[]', selectedFiles[i]);
        }
       
        
        ruta = '{{route('ex')}}'

        // Enviar la petición POST al servidor utilizando fetch
        fetch(ruta, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
        });
  })

</script>
