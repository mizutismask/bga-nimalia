/**
 * Base class for animations.
 */
abstract class NimaliaAnimation {
	protected zoom: number;

	constructor(protected game: NimaliaGame) {
		this.zoom = this.game.getZoom();
	}

	public abstract animate(): Promise<NimaliaAnimation>;
}
