jQuery(function() {
  var wrapper = document.getElementById("signature-pad");
  var canvas = wrapper.querySelector("canvas");
  var signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)'
  });

  function resizeCanvas() {
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    // signaturePad.clear();
  }
  window.onresize = resizeCanvas;
  resizeCanvas();

  $('#gd9-regular-donate-form').on('submit', function(e){
    e.preventDefault();
    const data = signaturePad.toDataURL("image/png");
    $('#gd9-regular-donate-form #edit-signature').val(data);
    $('#gd9-regular-donate-form')[0].submit();
  })
});
