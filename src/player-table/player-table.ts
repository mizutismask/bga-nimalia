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
			<span class="nml-player-name">${player.name}</span>
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
			dojo.connect($(squareId), 'drop', this, dojo.hitch(this, this.onCardDrop))
			dojo.connect($(squareId), 'dragover', this, dojo.hitch(this, this.onCardDropOver))
			dojo.connect($(squareId), 'touchend', this, dojo.hitch(this, this.onCardDropOver))
		}
	}

	public displayGrid(player: NimaliaPlayer, cards: Array<NimaliaCard>) {
		dojo.query(`#reserve-${player.id} .nml-square`).empty();
		cards.forEach((c) => {
			dojo.create(
				'div',
				{
					id: this.game.cardsManager.getId(c),
					style: getBackgroundInlineStyleForNimaliaCard(c),
					class: 'nimalia-card card-side front nml-card-order-' + c.order,
					'data-rotation': c.rotation
				},
				`square-${player.id}-${c.location_arg}`
			)
		})
	}

	public replaceCardsInHand(cards: Array<NimaliaCard>) {
		console.log('add cards', cards)
		this.handStock.removeAll()
		this.handStock.addCards(cards)
		cards.forEach((c) => {
			const cardId = this.game.cardsManager.getId(c)
			dojo.attr(cardId, 'draggable', true)
			dojo.connect($(cardId), 'dragstart', this, dojo.hitch(this, this.onCardDragStart))
			dojo.connect($(cardId), 'touchmove', this, dojo.hitch(this, this.onCardDragStart))
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
		if (!(this.game as any).isCurrentPlayerActive()) {
			evt.dataTransfer.clearData()
			evt.preventDefault()
			evt.stopPropagation()
			return
		}
		// Add the target element's id to the data transfer object
		evt.dataTransfer?.setData('text/plain', evt.target.id)//we move the whole card
		//evt.dataTransfer.effectAllowed = 'move'
		//console.log('drag', evt.target.id)
	}

	private onCardDrop(evt) {
		// Add the target element's id to the data transfer object
		evt.dataTransfer.effectAllowed = 'move'
		evt.preventDefault()
		const cardId = evt.dataTransfer.getData('text/plain')
		const square = (evt.target as HTMLElement).closest('.nml-square')
		console.log('drop', cardId, 'to', square.id)
		if (cardId && square) {
			this.game.clientActionData.previousCardParentInHand = $(cardId).parentElement
			square.appendChild($(cardId))
			$(cardId).classList.add("local-change")
			/*this.handStock.removeCard(
				this.handStock.getCards().filter((c) => c.id == (this.game as any).getPart(cardId, -1))[0]
			)*/
		}
		dojo.toggleClass('place-card-button', 'disabled', !cardId || !square)
		dojo.toggleClass('cancel-button', 'disabled', !cardId || !square)
		this.game.clientActionData.destinationSquare = square.id
		this.game.clientActionData.placedCardId = cardId
	}

	private onCardDropOver(evt) {
		evt.preventDefault()
		if (evt.target.classList && evt.target.classList.contains('dropzone')) {
			evt.dataTransfer.dropEffect = 'move'
		} else {
			evt.dataTransfer.dropEffect = 'none'
		}
		console.log('onCardDropOver')
	}
}
