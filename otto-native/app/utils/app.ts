import { File } from "@nativescript/core";
import { isIOS } from "@nativescript/core/platform";

export const requestErrorCallback = (
  error: any,
  options?: { title?: string; message?: string }
) => {
  alert({
    title: options?.title || "Error!",
    message: `${
      options?.message || "Unexpected error, please try again later."
    } ${error?.message || JSON.stringify(error)}`,
    okButtonText: "OK",
    cancelable: true,
  });
};

export const getBase64String = (sourceFile: File) => {
  const data = sourceFile.readSync();

  if (isIOS) {
    return data.base64EncodedStringWithOptions(0);
  } else {
    return android.util.Base64.encodeToString(
      data,
      android.util.Base64.NO_WRAP
    );
  }
};

export const secondsToTime = (seconds: number): string => {
  const pad = (n: any, z = 2) => ("00" + n).slice(-z);
  const ms = seconds % 1000;
  seconds = (seconds - ms) / 1000;
  const secs = seconds % 60;
  seconds = (seconds - secs) / 60;
  const mins = seconds % 60;

  return pad(mins) + ":" + pad(secs);
};
