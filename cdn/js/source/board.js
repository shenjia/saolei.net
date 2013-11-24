app.config.board = {
    'beg' : { 'x' : 8, 'y' : 8 },
    'int' : { 'x' : 16, 'y' : 16 },
    'exp' : { 'x' : 30, 'y' : 16 }
};

app.drawBoard = function (id) {
    var container = $('#board_' + id);
    if (container.data('drawed')) return;
    
    var level = container.data('level'),
        boardX = app.config.board[level].x,
        boardY = app.config.board[level].y,
        table = $('<table cellpadding="0" cellspacing="0"></table>'),
        board = container.attr('data-board').replace(/\*/g, 'b').split('');

    for (var y = 1; y <= boardY; y++) {
        var line = '';
        for (var x = 1; x <= boardX; x++) {
            line += '<td class="b' + board.shift() + '"></td>';
        }
        table.append($('<tr>' + line + '</tr>'));
    }

    container.append(table);
    container.data('drawed', true);
};
