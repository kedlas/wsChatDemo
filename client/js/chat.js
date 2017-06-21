/**
 *
 * DO NOT FORGET TO EDIT SERVER ADDR
 *
 */
var serverAddr = 'ws://10.211.55.6:8080';
var conn = null;

$(document).ready(function () {
    initWebSockets();

    $("#joinChat").click(function () {
      joinChat($("#username").val());
    });

    $("#submit").click(function () {
      addMessage($("#content").val());
      $("#content").val('');
    });
});


/**
 * Create websocket connection to server
 */
function initWebSockets() {
  conn = new WebSocket(serverAddr);

  conn.onopen = function () {
    console.log("Connection established!");
  };

  conn.onclose = function () {
    console.log("Connection closed.");
    closeWebSockets();
    alert('Connection has been closed');
  };

  conn.onerror = function (e) {
    conn = null;
    console.log("Connection to server failed." + e);
    closeWebSockets();
    alert('Connection encountered error.');
  };

  conn.onmessage = function (e) {
    console.log(e.data);

    var msg = null;
    try {
      msg = JSON.parse(e.data);
    } catch (err) {
      console.log(err);
    }

    if (msg.type === 'info') {
      alert('Info: ' + msg.data.message);
    } if (msg.type === 'error') {
      alert('Error: ' + msg.data.message);
    } else if (msg.type === 'add_user') {
      showChat(msg.data.messages);
    } else if (msg.type === 'message') {
      prependMessage(msg.data);
    }
  };
}

/**
 * Close current websockets connection
 */
function closeWebSockets() {
  conn.close();
  conn = null;
}

function joinChat(username) {
    var msg = {
        type: "add_user",
        data: {
            username: username
        }
    };

    conn.send(JSON.stringify(msg));
}

function showChat(messages) {
  $("#chatEntry").hide();
  $("#chatRowsWrapper").show();
  $("#chatInput").show();
  messages.forEach(function (msg) {
    prependMessage(msg)
  });
}

function prependMessage(msg) {
  var row = '<tr><td class="author">' + msg.author + '</td><td>' + msg.content + '</td></tr>';
  $("#chatRows").prepend(row);
}

/**
 * TODO - implement
 *
 * Send join game request to server
 *
 * @param {String} username
 */
function changeName(username) {
  var msg = {
    type: "rename_user",
    data: {
      username: username
    }
  };

  conn.send(JSON.stringify(msg));
}


/**
 * Add new chat message
 *
 * @param {String} content
 * @return {null}
 */
function addMessage(content) {
  var msg = {
    type: 'message',
    data: {
      content: content
    }
  };

  conn.send(JSON.stringify(msg));
}

