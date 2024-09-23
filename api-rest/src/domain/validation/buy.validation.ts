import * as joi from 'joi';

export const buyGetCodeSchema = joi.object({
  document: joi.string().required(),
  phone: joi.number().required(),
});
