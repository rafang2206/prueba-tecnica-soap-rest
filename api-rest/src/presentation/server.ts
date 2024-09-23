import express, { Express } from 'express';
import { AppRoutes } from './routes';
import { logger } from '../config/logger';

export class Server {

  public readonly app: Express;
  
  constructor(private readonly port: number) {
    this.app = express();
  }

  async start(){
    this.app.use(express.json());

    const appRoutes = AppRoutes.routes;

    this.app.use('/api', appRoutes);

    const listenServer = this.app.listen(this.port, () => {
      logger.info(`Server run in port ${this.port}`)
    })
    return listenServer;
  }
}