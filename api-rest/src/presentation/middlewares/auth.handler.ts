import { Request, Response, NextFunction } from 'express';
import { logger } from '../../config/logger';
import { HTTP_STATUS } from '../../constants/httpStatus';

export default function authHandler() {
  return (req: Request, res: Response, next: NextFunction) => {
    const sessionId = req.headers.authorization?.split("Bearer")[1];
    if(!sessionId){
      return next(
        res
          .status(HTTP_STATUS.BAD_REQUEST)
          .json({ cod_error: HTTP_STATUS.FORBIDDEN, success: false, message_error: 'Session Id Not Found' })
      )
    }
    req.sessionId = sessionId.trim();
    next();
  }
}