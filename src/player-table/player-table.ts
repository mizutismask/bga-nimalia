/**
 * Player table.
 */
class PlayerTable {
    constructor(game: NimaliaGame, player: NimaliaPlayer) {
        let html = `
			<a id="anchor-player-${player.id}"></a>
            <div id="player-table-${player.id}" class="player-order${player.playerNo} player-table ${
            player.id === game.getCurrentPlayer().id ? 'own' : ''
        }">
			<span class="player-name">${player.name}</span>
            </div>
        `;
        dojo.place(html, 'player-tables');
    }
}
