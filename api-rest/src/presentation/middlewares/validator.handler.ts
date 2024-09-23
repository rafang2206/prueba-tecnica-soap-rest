import { Request, Response, NextFunction } from 'express';
import { logger } from '../../config/logger';
import { HTTP_STATUS } from '../../constants/httpStatus';

export default function validatorHandler(schema: any, property: string) {
  return (req: Request, res: Response, next: NextFunction) => {
    try {
      for (const key in req[property as keyof Request]) {
        if (
          typeof req[property as keyof Request][key] == 'string' &&
          req[property as keyof Request][key].startsWith('{')
        ) {
          req[property as keyof Request][key] = JSON.parse(req[property as keyof Request][key]);
        }
      }
      const result = schema.validate(req[property as keyof Request]);
      if (result?.error?.details?.length > 0) {
        throw new Error(result?.error?.details[0].message);
      }
      logger.info(result);
      next();
    } catch (error: any) {
      logger.error(error.message);
      return res
        .status(HTTP_STATUS.BAD_REQUEST)
        .json({ message_error: error.message, success: false, code_error: HTTP_STATUS.BAD_REQUEST });
    }
  };
}
