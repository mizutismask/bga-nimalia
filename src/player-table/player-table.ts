/**
 * Player table.
 */
class PlayerTable {
	/** Stock for destinations in "done" column */
	private handStock: LineStock<NimaliaCard>

	constructor(private game: NimaliaGame, player: NimaliaPlayer) {
		const isMyTable = player.id === game.getCurrentPlayer().id
		const ownClass = isMyTable ? 'own' : ''
		let html = `
			<a id="anchor-player-${player.id}"></a>
			<span class="player-name">${player.name}</span>
            <div id="player-table-${player.id}" class="player-order${player.playerNo} player-table ${ownClass}">
                <div id="reserve-${player.id}" class="nml-reserve"></div>
            </div>
        `
		dojo.place(html, 'player-tables')
		this.setupReserve(player)

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

	private setupReserve(player: NimaliaPlayer) {
		const divId = `reserve-${player.id}`
		for (let i = 0; i < 36; i++) {
			const squareId = `square-${player.id}-${i + 1}`
			dojo.place(
				`
            <div id="${squareId}" class="nml-square">
            `,
				divId
			)
			$(squareId).addEventListener('drop', this.onCardDrop)
			$(squareId).addEventListener('dragover', this.onCardDropOver)
			$(squareId).addEventListener('touchend', this.onCardDropOver)
		}
	}

	public addCardsToHand(cards: Array<NimaliaCard>) {
		console.log('add cards', cards)
		this.handStock.addCards(cards)
		cards.forEach((c) => {
			const cardId = this.game.cardsManager.getId(c)
			dojo.attr(cardId, 'draggable', true)
			$(cardId).addEventListener('dragstart', this.onCardDragStart)
			$(cardId).addEventListener('touchmove', this.onCardDragStart)
		})
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

	private onCardDragStart(evt) {
		// Add the target element's id to the data transfer object
		evt.dataTransfer?.setData('text/plain', evt.target.id)
		//evt.dataTransfer.effectAllowed = 'move'
		//console.log('drag', evt.target.id)
	}

	private onCardDrop(evt) {
		// Add the target element's id to the data transfer object
		evt.dataTransfer.effectAllowed = 'move'
		evt.preventDefault();
		const cardId = evt.dataTransfer.getData('text/plain')
		const squareId = (evt.target as HTMLElement).closest(".nml-square");
		console.log('drop', cardId,"to", squareId)
	}

	private onCardDropOver(evt) {
		evt.preventDefault()
		evt.dataTransfer.dropEffect = 'move'
		console.log('onCardDropOver')
	}
}
