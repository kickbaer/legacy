function handleDragStart(e) {
    this.style.opacity = '0.8'; // this / e.target is the source node.
    dragSrcEl = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault(); // Necessary. Allows us to drop.
    }

    e.dataTransfer.dropEffect = 'move'; // See the section on the DataTransfer object.

    return false;
}

function handleDragEnter(e) {
// this / e.target is the current hover target.
    this.classList.add('over');
}

function handleDragLeave(e) {
    this.classList.remove('over'); // this / e.target is previous target element.
}

function handleDrop(e) {
// this/e.target is current target element.

    if (e.stopPropagation) {
        e.stopPropagation(); // Stops some browsers from redirecting.
    }

// Don't do anything if dropping the same column we're dragging.
    if (dragSrcEl != this) {
        if (this.parentNode.className == "col-1-5" && dragSrcEl.parentNode.className == "col-1-5") {
            switchPlayer(this, e);
        }
        else if (this.parentNode.className == "col-1-5" && dragSrcEl.parentNode.className == "col-1-6") {
            copyPlayer(this, e);
        }
    }
    return false;
}

function copyPlayer(to, e) {
// Set the source column's HTML to the HTML of the columnwe dropped on.
    to.innerHTML = e.dataTransfer.getData('text/html');
    to.classList.remove('over');
    to.style.opacity = '1';
    dragSrcEl.style.opacity = '1';
    checkLineup();
}

function radioGetValue(radio) {
    for (var i = 0, length = radio.length; i < length; i++) {
        if (radio[i].checked) {
// do whatever you want with the checked radio
            return(radio[i].value);
        }
    }
}

function setOtherRadio(clicked, other) {
    radio = document.getElementsByName(other);
    radio[8].checked = true;
}

function checkLineup() {
    var p1,p2,p3,p4,score1,score2
    var elo1 = 0;
    var elo2 = 0;
    document.getElementById('added').style.opacity = 0;
    if (document.getElementById('p1').getElementsByTagName('div')[0]) {
        p1 = document.getElementById('p1').getElementsByTagName('div')[0].getAttribute('data-playerID');
//        elo1 += document.getElementById('p1').getElementsByClassName('cardElo')[0].getElementsByTagName('progress')[0].value
    }
    if (document.getElementById('p2').getElementsByTagName('div')[0]) {
        p2 = document.getElementById('p2').getElementsByTagName('div')[0].getAttribute('data-playerID');
//        elo1 += document.getElementById('p2').getElementsByClassName('cardElo')[0].getElementsByTagName('progress')[0].value
    }
    if (document.getElementById('p3').getElementsByTagName('div')[0]) {
        p3 = document.getElementById('p3').getElementsByTagName('div')[0].getAttribute('data-playerID');
//        elo2 += document.getElementById('p3').getElementsByClassName('cardElo')[0].getElementsByTagName('progress')[0].value
    }
    if (document.getElementById('p4').getElementsByTagName('div')[0]) {
        p4 = document.getElementById('p4').getElementsByTagName('div')[0].getAttribute('data-playerID');
//        elo2 += document.getElementById('p4').getElementsByClassName('cardElo')[0].getElementsByTagName('progress')[0].value
    }
    score1 = radioGetValue(document.getElementsByName('score1'));
    score2 = radioGetValue(document.getElementsByName('score2'));
    if ((p1 && p2 && p3 && p4 && score1 && score2) && (p1!=p2&&p1!=p3&p1!=p4&&p2!=p3&&p2!=p4&&p3!=p4)) {
        document.getElementById('player1Id').value = p1;
        document.getElementById('player2Id').value = p2;
        document.getElementById('player3Id').value = p3;
        document.getElementById('player4Id').value = p4;
//	console.log(document.getElementById('player1Id').value);
//	console.log(document.getElementById('player2Id').value);
//	console.log(document.getElementById('player3Id').value);
//	console.log(document.getElementById('player4Id').value);
        document.getElementById('submit').style.visibility = "visible";
    }
    else {
        document.getElementById('submit').style.visibility = "hidden";
    }
}

function switchPlayer(to, e) {
// Set the source column's HTML to the HTML of the columnwe dropped on.
    dragSrcEl.innerHTML = to.innerHTML;
    to.innerHTML = e.dataTransfer.getData('text/html');
    to.classList.remove('over');
    to.style.opacity = '1';
    dragSrcEl.style.opacity = '1';
    checkLineup();
}

function handleDragEnd(e) {
    this.classList.remove('over');
    this.style.opacity = '1';
}

function main() {
    setables = document.querySelectorAll(".setable");
    [].forEach.call(setables, function(setable) {
        setable.addEventListener('dragstart', handleDragStart, false);
        setable.addEventListener('dragenter', handleDragEnter, false);
        setable.addEventListener('dragover', handleDragOver, false);
        setable.addEventListener('dragleave', handleDragLeave, false);
        setable.addEventListener('drop', handleDrop, false);
        setable.addEventListener('dragend', handleDragEnd, false);
    });
    score1 = document.getElementsByName('score1');
    score2 = document.getElementsByName('score2');
    for (var i = 0, iLen = score1.length; i < iLen; i++) {
        score1[i].checked = false;
	if (i!=iLen){
	  score1[i].onchange = function() {
	      setOtherRadio(this.value, 'score2');
	      checkLineup();
	  };
	}
    }
    for (var i = 0, iLen = score2.length; i < iLen; i++) {
        score2[i].checked = false;
	if (i!=iLen){
	  score2[i].onchange = function() {
	      setOtherRadio(this.value, 'score1');
	      checkLineup();
	  };
	}
    }
}
main();
