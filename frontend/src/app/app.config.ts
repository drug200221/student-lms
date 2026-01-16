import { ApplicationConfig, LOCALE_ID, provideBrowserGlobalErrorListeners } from '@angular/core';
import {
  ApplicationConfig,
  importProvidersFrom, inject, InjectionToken,
  LOCALE_ID,
  provideBrowserGlobalErrorListeners, Type
} from '@angular/core';
import { provideRouter, withRouterConfig } from '@angular/router';
import { NgxEditorModule } from 'ngx-editor';
import { CLIPBOARD_OPTIONS, ClipboardButtonComponent, provideMarkdown } from 'ngx-markdown';
import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [
    provideBrowserGlobalErrorListeners(),
    provideRouter(routes, withRouterConfig({
      paramsInheritanceStrategy: 'always',
    })),
    importProvidersFrom(NgxEditorModule.forRoot({
      locals: {
        // menu
        bold: 'Жирный',
        italic: 'Курсив',
        code: 'Код',
        blockquote: 'Цитата',
        underline: 'Подчеркнутый',
        strike: 'Зачеркнутый',
        bullet_list: 'Маркированный список',
        ordered_list: 'Нумерованный список',
        heading: 'Заголовок',
        h1: 'Заголовок 1',
        h2: 'Заголовок 2',
        h3: 'Заголовок 3',
        h4: 'Заголовок 4',
        h5: 'Заголовок 5',
        h6: 'Заголовок 6',
        align_left: 'По левому краю',
        align_center: 'По центру',
        align_right: 'По правому краю',
        align_justify: 'По ширине',
        text_color: 'Цвет текста',
        background_color: 'Цвет фона',

        // Попапы, формы и прочее
        url: 'URL',
        text: 'Текст',
        openInNewTab: 'В новой вкладке',
        insert: 'Вставить',
        altText: 'Альтернативный текст',
        title: 'Заголовок',
        remove: 'Удалить',
        enterValidUrl: 'Не корректный URL',
        required: 'Обязательно для заполнения',
      },
    })),
    provideMarkdown({
      clipboardOptions: {
        provide: CLIPBOARD_OPTIONS,
        useValue: {
          buttonComponent: ClipboardButtonComponent,
        },
      },
    }),
    {
      provide: LOCALE_ID,
      useValue: 'ru-RU',
    },
  ],
};
