declare const playSound

/**
 * End score board.
 * No notifications.
 */
class ScoreBoard {
	constructor(
		private game: NimaliaGame,
		private players: NimaliaPlayer[],
	) {
		const headers = document.getElementById('scoretr')
		if (!headers.childElementCount) {
			dojo.place(
				`
                <th> </th>
                <th colspan="3">${_('Round 1')}</th>
                <th colspan="3">${_('Round 2')}</th>
                <th colspan="3">${_('Round 3')}</th>
                <th colspan="4">${_('Round 4')}</th>
                <th colspan="4">${_('Round 5')}</th>
                <th id="th-total-score" class="">${_('Total')}</th>
            `,
				headers
			)
			dojo.place(
				`
                <thead>
                    <th> </th>
                    <th id="th-score-goal-blue" class="score-goal score-goal-blue"></th>
                    <th id="th-score-goal-green" class="score-goal score-goal-green"></th>
                    <th id="th-round-score" class="total-score">∑</th>

                    <th id="th-score-goal-green" class="score-goal score-goal-green"> </th>
                    <th id="th-score-goal-yellow" class="score-goal score-goal-yellow"> </th>
                    <th id="th-round-score" class="total-score">∑</th>

                    <th id="th-score-goal-blue" class="score-goal score-goal-blue"> </th>
                    <th id="th-score-goal-red" class="score-goal score-goal-red"> </th>
                    <th id="th-round-score" class="total-score">∑</th>

                    <th id="th-score-goal-green" class="score-goal score-goal-green"> </th>
                    <th id="th-score-goal-yellow" class="score-goal score-goal-yellow"> </th>
                    <th id="th-score-goal-red" class="score-goal score-goal-red"> </th>
                    <th id="th-round-score" class="total-score">∑</th>

                    <th id="th-score-goal-blue" class="score-goal score-goal-blue"> </th>
                    <th id="th-score-goal-red" class="score-goal score-goal-red"> </th>
                    <th id="th-score-goal-yellow" class="score-goal score-goal-yellow"> </th>
                    <th id="th-round-score" class="total-score">∑</th>

                    <th></th>
                <thead/>
            `,

				headers.parentElement,
				'after'
			)
		}

		players.forEach((player) => {
			const playerId = Number(player.id)

			dojo.place(
				`<tr id="score${player.id}">
                    <td id="score-name-${player.id}" class="player-name" style="color: #${player.color}">
                        <span id="score-winner-${player.id}"/> <span>${player.name}</span>
                    </td>
                    <td id="round-1-goal-2-${player.id}" class="score-number">${0}</td>
                    <td id="round-1-goal-1-${player.id}" class="score-number">${0}</td>
                    <td id="total-round-1-${player.id}" class="score-number total">0</td>

                    <td id="round-2-goal-1-${player.id}" class="score-number">${0}</td>
                    <td id="round-2-goal-4-${player.id}" class="score-number">${0}</td>
                    <td id="total-round-2-${player.id}" class="score-number total">0</td>

                    <td id="round-3-goal-2-${player.id}" class="score-number">${0}</td>
                    <td id="round-3-goal-3-${player.id}" class="score-number">${0}</td>
                    <td id="total-round-3-${player.id}" class="score-number total">0</td>

                    <td id="round-4-goal-1-${player.id}" class="score-number">${0}</td>
                    <td id="round-4-goal-4-${player.id}" class="score-number">${0}</td>
                    <td id="round-4-goal-3-${player.id}" class="score-number">${0}</td>
                    <td id="total-round-4-${player.id}" class="score-number total">0</td>

                    <td id="round-5-goal-2-${player.id}" class="score-number">${0}</td>
                    <td id="round-5-goal-3-${player.id}" class="score-number">${0}</td>
                    <td id="round-5-goal-4-${player.id}" class="score-number">${0}</td>
                    <td id="total-round-5-${player.id}" class="score-number total">0</td>
                    
                    <td id="total-${player.id}" class="score-number total">${player.score}</td>
                </tr>`,
				'score-table-body'
			)
		})

		//todo highlight winners
	}

	public updateScore(playerId: number, scoreType: string, score: number) {
		const elt = dojo.byId(scoreType)
		//if (elt.innerHTML != score.toString()) {
			elt.innerHTML = score.toString()
            dojo.addClass(scoreType, "animatedScore")
		//}
	}

	/**
	 * Add trophee icon to top score player(s)
	 */
    public highlightWinnerScore(playerId: number | string) {
        console.log(playerId)
        console.log(`total-${playerId}`)
		document.getElementById(`total-${playerId}`).classList.add('highlight')
		document.getElementById(`score-winner-${playerId}`).classList.add('fa', 'fa-trophy', 'fa-lg')
	}
}
