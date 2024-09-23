import { Request, Response} from 'express';
import winston from 'winston';
import morgan from 'morgan';

const { combine, timestamp, printf, colorize, align } = winston.format;
const logger = winston.createLogger({
  level: 'http',
  defaultMeta: {
    service: 'admin-service',
  },
  format: combine(
    timestamp({
      format: 'YYYY-MM-DD hh:mm:ss',
    }),
    align(),
    printf(
      (info) =>
        `${info.timestamp} ${info.level} [CookieCutterService]: [${
          info?.controller || ''
        }] ${info.message}  ${info?.data ? `data:${info.data}` : ''}`,
    ),
  ),
  transports: [
    new winston.transports.File({
      filename: 'combined.log',
      dirname: 'logs',
    }),
    new winston.transports.Console({
      format: combine(
        colorize({
          all: true,
        }),
      ),
    }),
  ],
});

const morganMiddleware = morgan(
  function (tokens: any, req: Request, res: Response) {
    return JSON.stringify({
      ip: req.headers['x-forwarded-for'] || req.connection.remoteAddress,
      method: tokens.method(req, res),
      url: tokens.url(req, res),
      body: req.body,
    });
  },
  {
    stream: {
      write: (json: string) => {
        const data = JSON.parse(json);
        if (data?.body?.password) {
          data.body.password = '******************';
        }
        const message = `From Ip ${data.ip} :${data?.method} request to :${data?.url} ${
          Object.keys(data?.body).length
            ? `with body: ${JSON.stringify(data?.body ?? {})}`
            : ''
        }`;
        if (data?.url == '/api/bad-credentials') {
          logger.error(message);
        } else {
          logger.http(message);
        }
      },
    },
    immediate: true,
  },
);

export { logger, morganMiddleware };
