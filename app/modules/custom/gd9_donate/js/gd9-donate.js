window.addEventListener('load', function () {
  var wrapper = document.getElementById("signature-pad");
  if (wrapper) {
    var canvas = wrapper.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas, {
      backgroundColor: 'rgb(255, 255, 255)'
    });

    function resizeCanvas() {
      var ratio = Math.max(window.devicePixelRatio || 1, 1);
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
  if (form) {
    var validation = form.addEventListener('submit', function (event) {
      form.classList.add('was-validated');

      var sig = document.getElementById('edit-signature');
      // 서명 여부 검사
      if (signaturePad.isEmpty()) {
        sig.setCustomValidity('');
        wrapper.classList.add("is-invalid");
        alert('서명은 필수입니다.');
      } else {
        wrapper.classList.add("is-valid");
        sig.value = signaturePad.toDataURL("image/png");
      }
      // 검사 실패 시 알림
      if (form.checkValidity() === false) {
        event.preventDefault(); event.stopPropagation();
        var msg = '필수사항을 모두 기재하지 않았습니다. 한 번 더 살펴보고 필수사항을 모두 기재해주세요!';
        alert(msg);
        return false;
      }
    }, false);
  }
});

// http://postcode.map.daum.net/guide
// 우편번호 찾기 찾기 화면을 넣을 element
var element_wrap = document.getElementById('wrap');
function foldDaumPostcode() {
  element_wrap.style.display = 'none';
}

function sample3_execDaumPostcode() {
  var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
  new daum.Postcode({
    oncomplete: function (data) {
      var addr = ''; // 주소 변수
      var extraAddr = ''; // 참고항목 변수

      //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
      if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
        addr = data.roadAddress;
      } else { // 사용자가 지번 주소를 선택했을 경우(J)
        addr = data.jibunAddress;
      }

      // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
      if (data.userSelectedType === 'R') {
        // 법정동명이 있을 경우 추가한다. (법정리는 제외)
        // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
        if (data.bname !== '' && /[동|로|가]$/g.test(data.bname)) {
          extraAddr += data.bname;
        }
        // 건물명이 있고, 공동주택일 경우 추가한다.
        if (data.buildingName !== '' && data.apartment === 'Y') {
          extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
        }
        // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
        if (extraAddr !== '') {
          extraAddr = ' (' + extraAddr + ')';
        }
        // 조합된 참고항목을 해당 필드에 넣는다.
        document.getElementById("sample3_extraAddress").value = extraAddr;

      } else {
        document.getElementById("sample3_extraAddress").value = '';
      }

      // 우편번호와 주소 정보를 해당 필드에 넣는다.
      document.getElementById('sample3_postcode').value = data.zonecode;
      document.getElementById("sample3_address").value = addr;
      // 커서를 상세주소 필드로 이동한다.
      document.getElementById("sample3_detailAddress").focus();

      // iframe을 넣은 element를 안보이게 한다.
      // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
      element_wrap.style.display = 'none';

      // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
      document.body.scrollTop = currentScroll;
    },
    // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
    onresize: function (size) {
      element_wrap.style.height = size.height + 'px';
    },
    width: '100%',
    height: '100%'
  }).embed(element_wrap);

  // iframe을 넣은 element를 보이게 한다.
  element_wrap.style.display = 'block';
}
