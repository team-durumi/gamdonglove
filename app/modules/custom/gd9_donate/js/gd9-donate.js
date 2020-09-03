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
      
      var sig = document.getElementById('edit-signature');
      // 서명 여부 검사
      if(signaturePad.isEmpty()) {
        sig.setCustomValidity("");
      } else {
        sig.value = signaturePad.toDataURL("image/png");
      }

      // 이메일 datalist 도메인 자동완성!
      // https://css-tricks.com/email-domain-datalist-helper/
      
      if (form.checkValidity() === false) {
        event.preventDefault(); event.stopPropagation();
        var msg = '필수사항을 모두 기재하지 않았습니다. 한 번 더 살펴보고 필수사항을 모두 기재해주세요!';
        alert(msg);
        return false;
      }
    }, false);
  }
});
