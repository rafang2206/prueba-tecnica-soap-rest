import express, { Express } from 'express';
import { AppRoutes } from './routes';
import { logger } from '../config/logger';

export class Server {

  public readonly app: Express;
  private serverListen?: any;
  constructor(private readonly port: number) {
    this.app = express();
  }

  async start(){
    this.app.use(express.json());

    const appRoutes = AppRoutes.routes;

    this.app.use('/api', appRoutes);

    this.serverListen = this.app.listen(this.port, () => {
      logger.info(`Server run in port ${this.port}`)
    })
  }

  public async close(){
    return new Promise((resolve) => {
      if (this.serverListen) {
        this.serverListen?.close(() => {
          console.log('Test server has been stopped');
          resolve(true);
        })
      } else {
        resolve(true);
      }
    });
  }
}