import './js/trix/trix-editor';

import {startStimulusApp} from '@symfony/stimulus-bridge';

import PreviewController from "./controllers/PreviewController";

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

app.register('preview', PreviewController);

app.debug = process.env.NODE_ENV !== 'production';
