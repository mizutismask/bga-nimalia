/**
 * Player table.
 */
class PlayerTable {
	/** Stock for destinations in "done" column */
	private handStock: LineStock<NimaliaCard>

	constructor(private game: NimaliaGame, player: NimaliaPlayer) {
		const isMyTable = player.id === game.getCurrentPlayer().id
		const ownClass = isMyTable ? 'own' : '';
		let html = `
			<a id="anchor-player-${player.id}"></a>
			<span class="player-name">${player.name}</span>
            <div id="player-table-${player.id}" class="player-order${player.playerNo} player-table ${ownClass}">
                <div id="reserve-${player.id}" class="nml-reserve"></div>
            </div>
        `
		dojo.place(html, 'player-tables')

		if (isMyTable) {
			const handHtml = `
			<div id="previous-player-draft" class="nml-player-draft nml-previous-player"></div>
			<div id="hand-${player.id}" class="nml-player-hand"></div>
			<div id="next-player-draft" class="nml-player-draft nml-next-player"></div>
        `
			dojo.place(handHtml, `player-table-${player.id}`, 'last')
			this.initHand(player)
		}
	}

	private initHand(player: NimaliaPlayer) {
		var stockSettings = {
			center: true,
			gap: '100px',
			direction: 'row' as 'row',
			wrap: 'wrap' as 'wrap'
		}
		this.handStock = new LineStock<NimaliaCard>(this.game.cardsManager, $('hand-' + player.id), stockSettings)
		this.handStock.setSelectionMode('single')
	}

	public addCardsToHand(cards: Array<NimaliaCard>) {
		console.log("add cards",cards)
		this.handStock.addCards(cards)
		/*this.handStock.addCards([{
			"id": 20,
			"location": "hand",
			"location_arg": 2333092,
			"type": 1,
			"type_arg": 11,
			order:1,
			rotation:1,
		}])*/
	}
}
