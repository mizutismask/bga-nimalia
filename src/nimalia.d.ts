/**
 * Your game interfaces
 */
declare const define
declare const ebg
declare const $
declare const dojo: Dojo
declare const _
declare const g_gamethemeurl

// remove this if you don't use cards. If you do, make sure the types are correct . By default, some number are send as string, I suggest to cast to right type in PHP.
interface Card {
	id: number
	location: string
	location_arg: number
	type: number
	type_arg: number
}
interface NimaliaCard extends Card {
	order: number
	rotation: number
}

interface NimaliaPlayer extends Player {
	playerNo: number
}

interface Goal{
	id: number;
    level: number;
    color: number;
}

interface NimaliaGamedatas {
	current_player_id: string
	decision: { decision_type: string }
	game_result_neutralized: string
	gamestate: Gamestate
	gamestates: { [gamestateId: number]: Gamestate }
	neutralized_player_id: string
	notifications: { last_packet_id: string; move_nbr: string }
	playerorder: (string | number)[]
	players: { [playerId: number]: NimaliaPlayer }
	tablespeed: string
	lastTurn: boolean
	turnOrderClockwise: boolean
	// counters
	bestScore: number
	// Add here variables you set up in getAllDatas
	hand: Array<NimaliaCard>
	goals:Array<Goal>
}

interface NimaliaGame extends Game {
	cardsManager: CardsManager
	animationManager: AnimationManager
	getCurrentPlayer(): NimaliaPlayer
	getPlayerId(): number
	getPlayerScore(playerId: number): number
	setTooltip(id: string, html: string): void
	setTooltipToClass(className: string, html: string): void
}

interface EnteringChooseActionArgs {
	canPass: boolean
}

interface NotifPointsArgs {
	playerId: number
	points: number
}

interface NotifBestScoreArgs {
	bestScore: number
	players: NimaliaPlayer[]
}

interface NotifLongestPathArgs {
	playerId: number
}

interface NotifScorePointArgs {
	playerId: number
	points: number
}
