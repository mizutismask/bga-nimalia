declare const playSound

/**
 * End score board.
 * No notifications.
 */
class EndScore {
	constructor(
		private game: NimaliaGame,
		private players: NimaliaPlayer[],
		/** bestScore is the top score for the game */
		private bestScore: number
	) {
		const headers = document.getElementById('scoretr')
		if (!headers.childElementCount) {
            dojo.place(
                `
                <th colspan="3">_("Round 1")</th>
                <th colspan="3">_("Round 2")</th>
                <th colspan="3">_("Round 3")</th>
                <th colspan="4">_("Round 4")</th>
                <th colspan="4">_("Round 5")</th>
                <th id="th-total-score" class="">_("Total")</th>
            `,
                headers
            );
            console.log("parentNode", headers.parentNode);
            console.log("parentElement", headers.parentElement)
            dojo.place(
                `
                <thead>
                <tr>
                    <th id="th-score-goal-blue" class="score-goal score-goal-blue"></th>
                    <th id="th-score-goal-green" class="score-goal score-goal-green"></th>
                    <th id="th-round-score" class="">∑</th>

                    <th id="th-score-goal-blue" class="score-goal score-goal-green"> </th>
                    <th id="th-score-goal-green" class="score-goal score-goal-yellow"> </th>
                    <th id="th-round-score" class="">∑</th>

                    <th id="th-score-goal-blue" class="score-goal score-goal-blue"> </th>
                    <th id="th-score-goal-green" class="score-goal score-goal-red"> </th>
                    <th id="th-round-score" class="">∑</th>

                    <th id="th-score-goal-blue" class="score-goal score-goal-green"> </th>
                    <th id="th-score-goal-green" class="score-goal score-goal-yellow"> </th>
                    <th id="th-score-goal-green" class="score-goal score-goal-red"> </th>
                    <th id="th-round-score" class="">∑</th>

                    <th id="th-score-goal-blue" class="score-goal score-goal-blue"> </th>
                    <th id="th-score-goal-green" class="score-goal score-goal-red"> </th>
                    <th id="th-score-goal-green" class="score-goal score-goal-yellow"> </th>
                    <th id="th-round-score" class="">∑</th>
                <tr/>
                <thead/>
            `,
                
				headers.parentElement, "after"
			)
		}

		players.forEach((player) => {
			const playerId = Number(player.id)

			/*dojo.place(
                `<tr id="score${player.id}">
                    <td id="score-name-${player.id}" class="player-name" style="color: #${
                    player.color
                }"><span id="score-winner-${player.id}"/> <span>${player.name}</span></td>
                    <td id="destination-reached${player.id}" class="score-number">${
                    player.completedDestinations.length + player.sharedCompletedDestinationsCount
                }</td>
                    <td id="revealed-tokens-back${player.id}" class="score-number">${
                    player.revealedTokensBackCount
                }</td>
                    <td id="destination-unreached${player.id}" class="score-number">${this.preventMinusZero(
                    player.uncompletedDestinations?.length
                )}</td>
                    <td id="revealed-tokens-left${player.id}" class="score-number">${this.preventMinusZero(
                    player.revealedTokensLeftCount
                )}</td>
                    <td id="total${player.id}" class="score-number total">${player.score}</td>
                </tr>`,
                "score-table-body"
            );*/
		})

		this.setBestScore(bestScore)
		players.forEach((player) => {
			if (Number(player.score) == bestScore) {
				this.highlightWinnerScore(player.id)
			}
		})
	}

	public updateScores(players: NimaliaPlayer[]) {
		/*players.forEach((p) => {
            document.getElementById(`destination-reached${p.id}`).innerHTML = (
                p.completedDestinations.length + p.sharedCompletedDestinationsCount
            ).toString();
            document.getElementById(`revealed-tokens-back${p.id}`).innerHTML = p.revealedTokensBackCount.toString();
            document.getElementById(`destination-unreached${p.id}`).innerHTML = this.preventMinusZero(
                p.uncompletedDestinations?.length
            );
            document.getElementById(`revealed-tokens-left${p.id}`).innerHTML = this.preventMinusZero(
                p.revealedTokensLeftCount
            );
            document.getElementById(`total${p.id}`).innerHTML = p.score.toString();
        });*/
	}

	private preventMinusZero(score: number) {
		if (score === 0) {
			return '0'
		}
		return '-' + score.toString()
	}

	/**
	 * Add trophee icon to top score player(s)
	 */
	public highlightWinnerScore(playerId: number | string) {
		document.getElementById(`score${playerId}`).classList.add('highlight')
		document.getElementById(`score-winner-${playerId}`).classList.add('fa', 'fa-trophy', 'fa-lg')
	}

	/**
	 * Save best score.
	 */
	public setBestScore(bestScore: number) {
		this.bestScore = bestScore
	}
}
