window.addEventListener('load', function() {
  var wrapper = document.getElementById("signature-pad");
  if(wrapper) {
    var canvas = wrapper.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas, {
      backgroundColor: 'rgb(255, 255, 255)'
    });

    function resizeCanvas() {
      var ratio =  Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext("2d").scale(ratio, ratio);
    }
    window.onresize = resizeCanvas;
    resizeCanvas();

    var clearButton = document.getElementById("signature-pad-clear");
    clearButton.addEventListener("click", function (event) {
      event.preventDefault(); event.stopPropagation();
      signaturePad.clear();
    });
  }
  
  var form = document.getElementById('gd9-regular-donate-form');
  if(form) {
    var validation = form.addEventListener('submit', function(event) {
      form.classList.add('was-validated');
      if (form.checkValidity() === false) {
        event.preventDefault(); event.stopPropagation();
        alert('작성한 내용에 문제가 있어요. 한 번 더 살펴보고 고쳐주세요!');
        return false;
      }
      const data = signaturePad.toDataURL("image/png");
      document.getElementById('edit-signature').value = data;
    }, false);
  }
});
